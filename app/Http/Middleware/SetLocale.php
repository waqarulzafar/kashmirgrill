<?php

namespace App\Http\Middleware;

use App\Support\LocalizationManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(
        private readonly LocalizationManager $localizationManager,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $resolvedLocale = $this->localizationManager->resolveLocale(
            $request->route('locale'),
            $request->session()->get('locale')
        );

        app()->setLocale($resolvedLocale);
        URL::defaults(['locale' => $resolvedLocale]);
        $request->session()->put('locale', $resolvedLocale);

        return $next($request);
    }
}
