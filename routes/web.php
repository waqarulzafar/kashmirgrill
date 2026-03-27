<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DineInSlotController;
use App\Http\Controllers\Admin\LocalizationSettingsController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventDetailPageController;
use App\Http\Controllers\EventsPageController;
use App\Http\Controllers\MenuItemPageController;
use App\Http\Controllers\MenuPageController;
use App\Support\LocalizationManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

$localePattern = implode('|', app(LocalizationManager::class)->supportedLocaleCodes());

Route::middleware('set.locale')->group(function () use ($localePattern): void {
    Auth::routes();

    Route::get('/', function (LocalizationManager $localizationManager) {
        return redirect()->route('home', [
            'locale' => $localizationManager->defaultLocale(),
        ]);
    })->name('root');

    Route::get('/language/{locale}', function (Request $request, LocalizationManager $localizationManager, string $locale) {
        abort_unless($localizationManager->isSupportedLocale($locale), 404);

        $request->session()->put('locale', $locale);

        $redirectUrl = (string) $request->query('redirect', route('home', ['locale' => $locale]));
        $applicationRoot = url('/');

        if (! str_starts_with($redirectUrl, $applicationRoot) && ! str_starts_with($redirectUrl, '/')) {
            $redirectUrl = route('home', ['locale' => $locale]);
        }

        return redirect()->to($redirectUrl);
    })->where('locale', $localePattern)->name('locale.switch');

    Route::get('/events', function (LocalizationManager $localizationManager) {
        return redirect()->route('events', ['locale' => $localizationManager->defaultLocale()]);
    });
    Route::get('/events/{slug}', function (LocalizationManager $localizationManager, string $slug) {
        return redirect()->route('events.show', [
            'locale' => $localizationManager->defaultLocale(),
            'slug' => $slug,
        ]);
    });
    Route::get('/menu', function (LocalizationManager $localizationManager) {
        return redirect()->route('menu', ['locale' => $localizationManager->defaultLocale()]);
    });
    Route::get('/menu/{menuItem:slug}', function (LocalizationManager $localizationManager, string $menuItem) {
        return redirect()->route('menu.items.show', [
            'locale' => $localizationManager->defaultLocale(),
            'menuItem' => $menuItem,
        ]);
    });
    Route::get('/book-now', function (LocalizationManager $localizationManager) {
        return redirect()->route('book-now', ['locale' => $localizationManager->defaultLocale()]);
    });
    Route::get('/contact', function (LocalizationManager $localizationManager) {
        return redirect()->route('contact', ['locale' => $localizationManager->defaultLocale()]);
    });
    Route::get('/checkout', function (LocalizationManager $localizationManager) {
        return redirect()->route('checkout.create', ['locale' => $localizationManager->defaultLocale()]);
    });

    Route::prefix('{locale}')
        ->where(['locale' => $localePattern])
        ->group(function () {
            Route::view('/', 'pages.home')->name('home');
            Route::get('/events', EventsPageController::class)->name('events');
            Route::get('/events/{slug}', EventDetailPageController::class)->name('events.show');
            Route::get('/menu', MenuPageController::class)->name('menu');
            Route::get('/menu/{menuItem:slug}', MenuItemPageController::class)->name('menu.items.show');
            Route::get('/book-now', [BookingController::class, 'create'])->name('book-now');
            Route::get('/book-now/availability', [BookingController::class, 'availability'])->name('bookings.availability');
            Route::post('/book-now', [BookingController::class, 'store'])
                ->middleware('throttle:5,1')
                ->name('bookings.store');
            Route::get('/book-now/success', [BookingController::class, 'success'])->name('bookings.success');
            Route::view('/contact', 'pages.contact')->name('contact');

            Route::post('/cart/items', [CartController::class, 'add'])->name('cart.items.add');
            Route::patch('/cart/items/{menuItem}', [CartController::class, 'update'])->name('cart.items.update');
            Route::delete('/cart/items/{menuItem}', [CartController::class, 'remove'])->name('cart.items.remove');
            Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
            Route::get('/cart/drawer', [CartController::class, 'drawer'])->name('cart.drawer');

            Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout.create');
            Route::post('/checkout', [CheckoutController::class, 'store'])
                ->middleware('throttle:8,1')
                ->name('checkout.store');
            Route::post('/checkout/login', [CheckoutController::class, 'login'])
                ->middleware(['guest', 'throttle:8,1'])
                ->name('checkout.login');
            Route::get('/checkout/payment/stripe/success/{order}', [CheckoutController::class, 'stripeSuccess'])->name('checkout.payment.stripe.success');
            Route::get('/checkout/payment/stripe/cancel/{order}', [CheckoutController::class, 'stripeCancel'])->name('checkout.payment.stripe.cancel');
            Route::get('/checkout/payment/paypal/success/{order}', [CheckoutController::class, 'paypalSuccess'])->name('checkout.payment.paypal.success');
            Route::get('/checkout/payment/paypal/cancel/{order}', [CheckoutController::class, 'paypalCancel'])->name('checkout.payment.paypal.cancel');
            Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
            Route::get('/checkout/slots', [CheckoutController::class, 'slots'])->name('checkout.slots');
        });

    Route::middleware('auth')
        ->prefix('account')
        ->name('account.')
        ->group(function () {
            Route::get('/', [AccountController::class, 'dashboard'])->name('dashboard');
            Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
            Route::get('/orders/{order}', [AccountController::class, 'showOrder'])->name('orders.show');
            Route::get('/bookings', [AccountController::class, 'bookings'])->name('bookings');
            Route::get('/bookings/{booking}', [AccountController::class, 'showBooking'])->name('bookings.show');
            Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
            Route::put('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
            Route::put('/password', [AccountController::class, 'updatePassword'])->name('password.update');
            Route::delete('/', [AccountController::class, 'destroy'])->name('destroy');
        });

    Route::middleware(['auth', 'admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/', function () {
                return redirect()->route('admin.dashboard');
            })->name('home');
            Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');
            Route::get('localization', [LocalizationSettingsController::class, 'edit'])->name('localization.edit');
            Route::put('localization', [LocalizationSettingsController::class, 'update'])->name('localization.update');

            Route::resource('menu-categories', MenuCategoryController::class)
                ->except(['show']);

            Route::resource('menu-items', MenuItemController::class)
                ->except(['show']);

            Route::resource('dine-in-slots', DineInSlotController::class)
                ->except(['show']);

            Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
            Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
            Route::patch('bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');

            Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
            Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
            Route::patch('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');
        });
});
