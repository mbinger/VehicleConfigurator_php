<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/order/create', 'kfz.order.create');
Route::livewire('/order/{number}', 'kfz.order.details')->name('kfz.order.details');
Route::livewire('/order/{number}/edit', 'kfz.order.edit')->name('kfz.order.edit');
Route::livewire('/order/{number}/delete', 'kfz.order.delete')->name('kfz.order.delete');

Route::get('/', function ()
{
    return view('components.kfz.order.⚡index');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
