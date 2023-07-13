<?php

use App\Http\Controllers\CartItems\CartController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\Products\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolesController;
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
    Route::put('/users/{user}/permissions', [UserController::class, 'assignPermissionsToUser'])->name('users.assignPermissions');
    Route::put('/users/{user}/roles', [UserController::class, 'assignRolesToUser'])->name('users.assignRoles');

    Route::resource('/products', ProductController::class);
    Route::get('/permissions/index', [PermissionsController::class, 'index'])->name('permissions.index');
    Route::get('/roles/index', [RolesController::class, 'index'])->name('roles.index');
    Route::get('/roles/{role}', [RolesController::class, 'assignPermissionsToRole'])->name('roles.permissions');
});

require __DIR__ . '/auth.php';
