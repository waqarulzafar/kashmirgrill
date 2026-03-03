<?php

use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\BookingController;
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
Route::post('/book-now', [BookingController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('bookings.store');
Route::get('/book-now/success', [BookingController::class, 'success'])->name('bookings.success');
Route::view('/contact', 'pages.contact')->name('contact');

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.menu-categories.index');
        })->name('dashboard');

        Route::resource('menu-categories', MenuCategoryController::class)
            ->except(['show']);

        Route::resource('menu-items', MenuItemController::class)
            ->except(['show']);
    });
