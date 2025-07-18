<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// CAMBIO: Importar el controlador de logout
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {
    // Hemos desactivado el registro público
    // Volt::route('register', 'pages.auth.register')->name('register');

    Volt::route('ingresar', 'pages.auth.login')->name('login');

    Volt::route('olvido-contrasena', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('restablecer-contrasena/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Volt::route('verificar-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verificar-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirmar-password', 'pages.auth.confirm-password')
        ->name('password.confirm');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
