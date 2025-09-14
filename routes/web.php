<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MasterAuthController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\Auth\TenantAuthController;


Route::domain('{tenant}.pharmacy.local')->middleware(['tenant'])->group(function () {
    // Tenant login
    Route::get('/login', [TenantAuthController::class, 'showLoginForm'])->name('tenant.login');
    Route::post('/login', [TenantAuthController::class, 'login'])->name('tenant.login.attempt');

    // Tenant logout
    Route::post('/logout', [TenantAuthController::class, 'logout'])->name('tenant.logout');

    // Protected tenant routes
    Route::get('/tenant/dashboard', function () {
        return view('auth.tenant');
    })->name('tenant.dashboard');
});

Route::get('/login', [MasterAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [MasterAuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [MasterAuthController::class, 'logout'])->name('logout');

// Protected master routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('master_admin.dashboard');
    })->name('dashboard');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/tenants/create', [TenantController::class, 'create'])->name('tenants.create');
    Route::post('/tenants', [TenantController::class, 'store'])->name('tenants.store');
});
