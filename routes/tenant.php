<?php

use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\HomeController;
use Illuminate\Support\Facades\Route;

Route::domain('{tenant}.pharmacy.local')
    ->middleware(['tenant']) // set tenant DB first
    ->name('tenant.')
    ->group(function () {

        // Guest routes (login page)
        Route::middleware(['tenant.guest'])->group(function () {
            Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
            Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
        });

        // Authenticated tenant routes (dashboard, logout)
        Route::middleware(['tenant.auth'])->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('/tenant/dashboard', [HomeController::class, 'index'])->name('dashboard');
        });
    });
