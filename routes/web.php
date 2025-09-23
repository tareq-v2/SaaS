<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserImportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Authentication Routes
Auth::routes();

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

// Admin-specific routes with proper role middleware
Route::middleware(['auth', 'role:admin,super_admin'])->prefix('admin')->group(function () {
    // User import routes
    Route::get('/users/import', [UserImportController::class, 'showImportForm'])->name('admin.users.import.form');
    Route::post('/users/import', [UserImportController::class, 'import'])->name('admin.users.import');
    Route::get('/users/import/template', [UserImportController::class, 'downloadTemplate'])->name('admin.users.import.template');
    Route::get('/imports/{import}/status', [UserImportController::class, 'importStatus'])->name('admin.imports.status');
    Route::get('/imports/{import}/status-json', [UserImportController::class, 'importStatusJson'])->name('admin.imports.status.json');
    Route::get('/notifications/latest', [UserImportController::class, 'getLatestNotifications'])->name('admin.notifications.latest');
    Route::get('/imports/history', [UserImportController::class, 'importHistory'])->name('admin.imports.history');
});
