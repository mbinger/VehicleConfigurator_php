<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/create', 'kfz.order.create');
Route::livewire('/orders/{number}', 'kfz.order.details')->name('kfz.order.details');

Route::get('/', function ()
{
    return view('components.kfz.order.⚡index');
});

/*
Route::get('/create', function ()
{
    return view('components.kfz.order.⚡create');
});
*/

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
