<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\MasterAuthController;
use App\Http\Controllers\TenantController;

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