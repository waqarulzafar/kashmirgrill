<?php

namespace App\Providers;

use App\Services\Payments\Gateways\PayPalPaymentGateway;
use App\Services\Payments\Gateways\StripePaymentGateway;
use App\Services\Payments\PaymentGatewayManager;
use App\Support\CartManager;
use App\Support\LocalizationManager;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayManager::class, function () {
            return new PaymentGatewayManager([
                new StripePaymentGateway,
                new PayPalPaymentGateway,
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        URL::defaults([
            'locale' => app(LocalizationManager::class)->defaultLocale(),
        ]);

        View::composer('layouts.master', function ($view): void {
            $localizationManager = app(LocalizationManager::class);

            $view->with([
                'globalCart' => app(CartManager::class)->summary(),
                'siteLocales' => $localizationManager->localeSwitcher(request()),
                'siteCurrentLocale' => app()->getLocale(),
                'siteCurrentLocaleData' => $localizationManager->currentLocaleData(app()->getLocale()),
                'siteDefaultLocale' => $localizationManager->defaultLocale(),
                'localizedCurrentUrls' => $localizationManager->localizedUrlsFor(
                    request()->route(),
                    request()->route()?->parameters() ?? [],
                ),
            ]);
        });
    }
}
