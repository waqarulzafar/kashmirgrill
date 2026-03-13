<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\DineInSlotController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\EventDetailPageController;
use App\Http\Controllers\EventsPageController;
use App\Http\Controllers\MenuItemPageController;
use App\Http\Controllers\MenuPageController;
use Illuminate\Support\Facades\Route;

Auth::routes();

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
Route::get('/checkout/payment/stripe/success/{order}', [CheckoutController::class, 'stripeSuccess'])->name('checkout.payment.stripe.success');
Route::get('/checkout/payment/stripe/cancel/{order}', [CheckoutController::class, 'stripeCancel'])->name('checkout.payment.stripe.cancel');
Route::get('/checkout/payment/paypal/success/{order}', [CheckoutController::class, 'paypalSuccess'])->name('checkout.payment.paypal.success');
Route::get('/checkout/payment/paypal/cancel/{order}', [CheckoutController::class, 'paypalCancel'])->name('checkout.payment.paypal.cancel');
Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/slots', [CheckoutController::class, 'slots'])->name('checkout.slots');

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        })->name('home');
        Route::get('profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [AdminProfileController::class, 'update'])->name('profile.update');

        Route::resource('menu-categories', MenuCategoryController::class)
            ->except(['show']);

        Route::resource('menu-items', MenuItemController::class)
            ->except(['show']);

        Route::resource('dine-in-slots', DineInSlotController::class)
            ->except(['show']);
    });
