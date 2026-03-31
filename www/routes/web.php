<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cookie;

Route::get('locale/{locale?}', function ($locale = null) {
    if (isset($locale) && in_array($locale, config('app.available_locales'))) {
        //Cookie::queue('locale', $locale, 1440);
        app()->setLocale($locale);
        session()->put('locale', $locale);
        return back();
    }
})->name('locale');

Route::livewire('/order/create/{customer_number?}', 'kfz.order.create')->name('kfz.order.create');
Route::livewire('/order/search', 'kfz.order.search')->name('kfz.order.search');
Route::livewire('/order/{number}/edit', 'kfz.order.edit')->name('kfz.order.edit');
Route::livewire('/customer/search', 'kfz.customer.search')->name('kfz.customer.search');
Route::livewire('/customer/{number}', 'kfz.customer.orders')->name('kfz.customer.orders');
Route::livewire('/', 'kfz.index')->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
