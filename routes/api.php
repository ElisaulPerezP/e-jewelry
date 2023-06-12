<?php

use App\Http\Controllers\ApiCartController;
use App\Http\Controllers\ApiOrderController;
use App\Http\Controllers\ApiProductController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ApiProductController::class, 'index'])->name('api.products.index');

Route::middleware('auth:api')->name('api.')->group(function () {
    Route::prefix('/cart')->name('cart.')->group(function () {
        Route::get('/', [ApiCartController::class, 'index'])
            ->name('index');
        Route::put('/{cartItem}/setAmount', [ApiCartController::class, 'setAmount'])
            ->name('setAmount');
        Route::post('/{product}/store', [ApiCartController::class, 'store'])
            ->name('store')->middleware('auth');
        Route::delete('/{cartItem}/delete', [ApiCartController::class, 'destroy'])
            ->name('destroy');
        Route::put('/{cartItem}/changeState', [ApiCartController::class, 'changeState'])
            ->name('update.state.saved');
    });

    Route::prefix('/order')->name('order.')->group(function () {
        Route::get('/', [ApiOrderController::class, 'index'])
            ->name('index');
        Route::post('/create', [ApiOrderController::class, 'store'])
            ->name('store');
        Route::get('/{order}/checkStatus', [ApiOrderController::class, 'checkStatus'])
            ->name('show');
        Route::post('/{order}/retry', [ApiOrderController::class, 'retry'])
            ->name('retry');
    });

    Route::middleware('role:admin')->prefix('/products')->name('products.')->group(function () {
        Route::get('/{product}', [ApiProductController::class, 'show'])
            ->name('show');
        Route::post('/', [ApiProductController::class, 'store'])
            ->name('store');
        Route::put('/{product}', [ApiProductController::class, 'update'])
            ->name('update');
        Route::delete('/{product}', [ApiProductController::class, 'destroy'])
            ->name('destroy');
        Route::put('/{product}/changeStatus', [ApiProductController::class, 'changeStatus'])
            ->name('changeStatus');
    });
});
