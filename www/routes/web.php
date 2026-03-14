<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/order/create/{customer_number?}', 'kfz.order.create')->name('kfz.order.create');
Route::livewire('/order/search', 'kfz.order.search')->name('kfz.order.search');
Route::livewire('/order/{number}', 'kfz.order.details')->name('kfz.order.details');
Route::livewire('/order/{number}/edit', 'kfz.order.edit')->name('kfz.order.edit');
Route::livewire('/order/{number}/delete', 'kfz.order.delete')->name('kfz.order.delete');
Route::livewire('/customer/search', 'kfz.customer.search')->name('kfz.customer.search');
Route::livewire('/customer/{number}', 'kfz.customer.orders')->name('kfz.customer.orders');

Route::get('/', function ()
{
    return view('components.kfz.order.⚡index');
})->name('home');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
