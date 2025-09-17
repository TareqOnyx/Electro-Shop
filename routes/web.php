<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('index'); // public homepage
});



// Redirect after login (authenticated users only)
//Route::get('/', [UserController::class, 'Redirect'])->middleware('auth');



Route::middleware(['auth', 'isAdmin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::post('/categories', [DashboardController::class, 'addCategory'])->name('categories.store');
    Route::put('/categories/{id}', [DashboardController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [DashboardController::class, 'deleteCategory'])->name('categories.destroy');

    // Products
    Route::post('/products', [DashboardController::class, 'addProduct'])->name('products.store');
    Route::put('/products/{id}', [DashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [DashboardController::class, 'deleteProduct'])->name('products.destroy');
});




require __DIR__.'/auth.php';
