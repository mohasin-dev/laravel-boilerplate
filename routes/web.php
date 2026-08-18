<?php

use App\Http\Controllers\Auth\LogoutController;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', Login::class)->name('login');
});

Route::post('/logout', LogoutController::class)
    ->middleware('auth')
    ->name('logout');
