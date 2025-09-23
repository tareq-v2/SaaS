<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Authentication Routes
Auth::routes();

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Protected dashboard routes
Route::middleware(['auth'])->group(function () {
    // Main dashboard route (redirects based on role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Role-specific dashboards
    Route::get('/super-admin/dashboard', [DashboardController::class, 'superAdminDashboard'])
         ->name('super_admin.dashboard')
         ->middleware('role:super_admin');
         
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
         ->name('admin.dashboard')
         ->middleware('role:admin');
         
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
         ->name('user.dashboard')
         ->middleware('role:user');
});