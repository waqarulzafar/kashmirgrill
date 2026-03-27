<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route as RouteFacade;
use Illuminate\Support\Facades\Schema;

class LocalizationManager
{
    public function defaultLocale(): string
    {
        return Cache::rememberForever('site-settings.default-locale', function (): string {
            if (! Schema::hasTable('site_settings')) {
                return $this->normalizeLocale(config('app.locale'));
            }

            $storedLocale = SiteSetting::query()
                ->where('key', SiteSetting::KEY_DEFAULT_LOCALE)
                ->value('value');

            return $this->normalizeLocale($storedLocale);
        });
    }

    public function setDefaultLocale(string $locale): void
    {
        SiteSetting::query()->updateOrCreate(
            ['key' => SiteSetting::KEY_DEFAULT_LOCALE],
            ['value' => $this->normalizeLocale($locale)]
        );

        Cache::forget('site-settings.default-locale');
    }

    public function supportedLocales(): array
    {
        return config('locales.supported', []);
    }

    public function supportedLocaleCodes(): array
    {
        return array_keys($this->supportedLocales());
    }

    public function isSupportedLocale(?string $locale): bool
    {
        return is_string($locale) && array_key_exists($locale, $this->supportedLocales());
    }

    public function resolveLocale(?string $routeLocale = null, ?string $sessionLocale = null): string
    {
        if ($this->isSupportedLocale($routeLocale)) {
            return $routeLocale;
        }

        if ($this->isSupportedLocale($sessionLocale)) {
            return $sessionLocale;
        }

        return $this->defaultLocale();
    }

    public function currentLocaleData(?string $locale = null): array
    {
        $resolvedLocale = $this->normalizeLocale($locale);

        return $this->supportedLocales()[$resolvedLocale];
    }

    public function localeSwitcher(Request $request): array
    {
        $route = $request->route();
        $currentLocale = $this->resolveLocale(
            $this->routeLocale($route),
            $request->session()->get('locale')
        );

        return collect($this->supportedLocales())
            ->map(function (array $meta, string $locale) use ($currentLocale, $request, $route): array {
                return [
                    'code' => $locale,
                    'name' => $meta['name'],
                    'native' => $meta['native'],
                    'flag' => $meta['flag'],
                    'url' => $this->switcherUrl($request, $route, $locale),
                    'is_current' => $locale === $currentLocale,
                ];
            })
            ->values()
            ->all();
    }

    public function localizedUrlsFor(Route|string|null $route, array $parameters = []): array
    {
        $resolvedRoute = is_string($route) ? RouteFacade::getRoutes()->getByName($route) : $route;

        if (! $resolvedRoute instanceof Route) {
            return [];
        }

        if (! in_array('locale', $resolvedRoute->parameterNames(), true)) {
            return [];
        }

        $filteredParameters = collect($parameters)
            ->except('locale')
            ->all();

        $routeName = $resolvedRoute->getName();

        if (! is_string($routeName) || $routeName === '') {
            return [];
        }

        return collect($this->supportedLocales())
            ->mapWithKeys(function (array $meta, string $locale) use ($filteredParameters, $routeName): array {
                return [
                    $locale => route($routeName, ['locale' => $locale] + $filteredParameters),
                ];
            })
            ->all();
    }

    public function routeLocale(Route|string|null $route): ?string
    {
        $resolvedRoute = is_string($route) ? RouteFacade::getRoutes()->getByName($route) : $route;

        if (! $resolvedRoute instanceof Route) {
            return null;
        }

        $parameters = $resolvedRoute->parameters();

        return is_string($parameters['locale'] ?? null) ? $parameters['locale'] : null;
    }

    public function normalizeLocale(?string $locale): string
    {
        if ($this->isSupportedLocale($locale)) {
            return $locale;
        }

        $fallback = config('app.locale');

        if ($this->isSupportedLocale($fallback)) {
            return $fallback;
        }

        return $this->supportedLocaleCodes()[0] ?? 'en';
    }

    private function switcherUrl(Request $request, ?Route $route, string $locale): string
    {
        if ($route instanceof Route) {
            $localizedUrls = $this->localizedUrlsFor($route, $route->parameters());

            if (isset($localizedUrls[$locale])) {
                return $localizedUrls[$locale];
            }
        }

        return route('locale.switch', [
            'locale' => $locale,
            'redirect' => $request->fullUrl(),
        ]);
    }
}
