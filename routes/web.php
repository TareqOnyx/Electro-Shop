<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Show all products
Route::get('/products', [ProductController::class, 'ShowProductPage'])->name('products');

// Add a new product
Route::post('/products/add', [Product_Controller::class, 'AddProduct'])->name('add-product');

// Update a product (usually via form with PUT method)
Route::post('/products/update/{id}', [Product_Controller::class, 'UpdateProduct'])->name('update-product');

// Delete a product
Route::get('/products/delete/{id}', [Product_Controller::class, 'DeleteProduct'])->name('delete-product');

