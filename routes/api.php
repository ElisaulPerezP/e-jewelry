<?php

use App\Http\Controllers\Product\ApiProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('api')->group(function () {
    Route::get('/products', [ApiProductController::class, 'index'])->name('api.products.index');
    Route::get('/products/{product}', [ApiProductController::class, 'show'])->name('api.products.show');
    Route::post('/products', [ApiProductController::class, 'store'])->name('api.products.store');
    Route::put('/products/{product}', [ApiProductController::class, 'update'])->name('api.products.update');
    Route::delete('/products/{product}', [ApiProductController::class, 'destroy'])->name('api.products.destroy');
});
