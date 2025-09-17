<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\IsAdmin;

// Public homepage
Route::get('/', function () {
    return view('index');
});

// Dashboard + admin routes (protected for admins)
Route::middleware(['auth', IsAdmin::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories CRUD
    Route::post('/categories', [DashboardController::class, 'addCategory'])->name('categories.store');
    Route::put('/categories/{id}', [DashboardController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [DashboardController::class, 'deleteCategory'])->name('categories.destroy');

    // Products CRUD
    Route::post('/products', [DashboardController::class, 'addProduct'])->name('products.store');
    Route::put('/products/{id}', [DashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [DashboardController::class, 'deleteProduct'])->name('products.destroy');
});

// Profile routes (any authenticated user)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Include auth routes (login, register, etc.)
require __DIR__.'/auth.php';
