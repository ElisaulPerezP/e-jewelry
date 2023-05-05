<?php

use App\Http\Controllers\ApiProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/products', [ApiProductController::class, 'index'])->name('api.products.index');
    Route::get('/products/{product}', [ApiProductController::class, 'show'])->name('api.products.show');
    Route::post('/products', [ApiProductController::class, 'store'])->name('api.products.store');
    Route::put('/products/{product}', [ApiProductController::class, 'update'])->name('api.products.update');
    Route::delete('/products/{product}', [ApiProductController::class, 'destroy'])->name('api.products.destroy');
    Route::put('/products/changeStatus/{product}', [ApiProductController::class, 'changeStatus'])
        ->name('api.products.changeStatus');
});
