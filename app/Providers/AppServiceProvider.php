<?php

namespace App\Providers;

use App\Services\Payments\Gateways\PayPalPaymentGateway;
use App\Services\Payments\Gateways\StripePaymentGateway;
use App\Services\Payments\PaymentGatewayManager;
use App\Support\CartManager;
use Illuminate\Pagination\Paginator;
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
        View::composer('layouts.master', function ($view): void {
            $view->with('globalCart', app(CartManager::class)->summary());
        });
    }
}
