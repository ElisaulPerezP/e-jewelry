<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cart/{user}', [CartController::class, 'index'])->middleware(['auth', 'verified'])->name('cart');

Route::get('/order', [OrderController::class, 'index'])->middleware(['auth', 'verified'])->name('orders');
Route::get('/order/{order}/show', [OrderController::class, 'show'])->middleware(['auth', 'verified'])->name('orders.show');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/changeStatus/{user}', [UserController::class, 'changeStatus'])->name('users.changeStatus');
    Route::get('/users/show/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/products/show/{productId}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/products/edit/{productId}', [ProductController::class, 'edit'])->name('products.edit');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
});

require __DIR__ . '/auth.php';
