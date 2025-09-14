<?php

use App\Http\Controllers\Tenant\AuthController;
use App\Http\Controllers\Tenant\HomeController;
use Illuminate\Support\Facades\Route;

Route::domain('{tenant}.pharmacy.local')->middleware(['tenant'])->group(function () {
    // Tenant login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [AuthController::class, 'login'])->name('tenant.login.attempt');

    // Tenant logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('tenant.logout');

    // Protected tenant routes
    Route::get('/tenant/dashboard', [HomeController::class,'index'])->name('tenant.dashboard');
});
