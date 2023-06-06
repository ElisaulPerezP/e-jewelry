<?php

use App\Http\Controllers\ApiCartController;
use App\Http\Controllers\ApiProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/products', [ApiProductController::class, 'index'])->name('api.products.index');

Route::get('/cart/{user}', [ApiCartController::class, 'index'])->name('api.cart.index')->middleware('auth:api');
Route::put('/cart/{itemCart}/update/amount', [ApiCartController::class, 'updateAmount'])->name('api.cart.update.amount')->middleware('auth:api');
Route::post('/cart/{product}/store', [ApiCartController::class, 'store'])->name('api.cart.store')->middleware('auth:api', 'auth');
Route::delete('/cart/{itemCart}/delete', [ApiCartController::class, 'destroy'])->name('api.cart.destroy')->middleware('auth:api');
Route::put('/cart/{itemCart}/update/date', [ApiCartController::class, 'updateExpireDate'])->name('api.cart.update.date')->middleware('auth:api');
Route::put('/cart/{itemCart}/update/state/saved', [ApiCartController::class, 'updateItemStateToSaved'])->name('api.cart.update.state.saved')->middleware('auth:api');
Route::put('/cart/{itemCart}/update/state/incart', [ApiCartController::class, 'updateItemStateToInCart'])->name('api.cart.update.state.incart')->middleware('auth:api');
Route::put('/cart/{itemCart}/reset/amount', [ApiCartController::class, 'resetItemAmount'])->name('api.cart.reset.item.amount')->middleware('auth:api');


Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/products/{product}', [ApiProductController::class, 'show'])->name('api.products.show');
    Route::post('/products', [ApiProductController::class, 'store'])->name('api.products.store');
    Route::put('/products/{product}', [ApiProductController::class, 'update'])->name('api.products.update');
    Route::delete('/products/{product}', [ApiProductController::class, 'destroy'])->name('api.products.destroy');
    Route::put('/products/changeStatus/{product}', [ApiProductController::class, 'changeStatus'])
        ->name('api.products.changeStatus');
});
