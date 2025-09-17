<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
<<<<<<< HEAD
=======
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
>>>>>>> 9b9497d10bada0878d995b65772bc2a9adaaea05

Route::get('/', function () {
    return view('index'); // public homepage
});

<<<<<<< HEAD
=======


// Redirect after login (authenticated users only)
//Route::get('/', [UserController::class, 'Redirect'])->middleware('auth');




>>>>>>> 9b9497d10bada0878d995b65772bc2a9adaaea05
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
