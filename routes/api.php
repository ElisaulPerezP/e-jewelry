<?php

use App\Http\Controllers\ApiDTOController;
use App\Http\Controllers\ApiPermissionController;
use App\Http\Controllers\ApiRolesController;
use App\Http\Controllers\CartItems\ApiCartController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\Orders\ApiOrderController;
use App\Http\Controllers\Products\ApiProductController;
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
        Route::get('/total', [ApiCartController::class, 'total'])
            ->name('total');
    });

    Route::prefix('/order')->name('order.')->group(function () {
        Route::get('/', [ApiOrderController::class, 'index'])
            ->name('index');
        Route::get('/{order}/show', [ApiOrderController::class, 'show'])
            ->name('show');
        Route::post('/create', [ApiOrderController::class, 'store'])
            ->name('store');
        Route::get('/{order}/checkStatus', [ApiOrderController::class, 'checkStatus'])
            ->name('checkStatus');
        Route::post('/{order}/retry', [ApiOrderController::class, 'retry'])
            ->name('retry');
    });

    Route::get('/products/{product}', [ApiProductController::class, 'show'])
        ->middleware('role_or_permission:api.show.product|admin')
        ->name('products.show');

    Route::post('/products', [ApiProductController::class, 'store'])
        ->middleware('role_or_permission:api.store.product|admin')
        ->name('products.store');

    Route::put('/products/{product}', [ApiProductController::class, 'update'])
        ->middleware('role_or_permission:api.update.product|admin')
        ->name('products.update');

    Route::delete('/products/{product}', [ApiProductController::class, 'destroy'])
        ->middleware('role_or_permission:api.destroy.product|admin')
        ->name('products.destroy');

    Route::put('/products/{product}/changeStatus', [ApiProductController::class, 'changeStatus'])
        ->middleware('role_or_permission:api.changeStatus.product|admin')
        ->name('products.changeStatus');

    Route::get('/export/products', [ApiDTOController::class, 'export'])
        ->middleware('role_or_permission:api.export.product|admin')
        ->name('api.export.products');
    Route::post('/import/products', [ImportController::class, 'store'])
        ->middleware('role_or_permission:api.import.product|admin')
        ->name('api.import.products');

    Route::middleware('role:admin')->prefix('/permissions')->name('permissions.')->group(function () {
        Route::get('/', [ApiPermissionController::class, 'index'])
            ->name('index');
    });

    Route::middleware('role:admin')->prefix('/roles')->name('roles.')->group(function () {
        Route::delete('/{role}', [ApiRolesController::class, 'delete'])
            ->name('delete');
        Route::get('/{role}/permissions', [ApiRolesController::class, 'showPermissionsToRole'])
            ->name('permissions');
        Route::post('/store', [ApiRolesController::class, 'store'])
            ->name('store');
        Route::get('/', [ApiRolesController::class, 'index'])
            ->name('index');
        Route::post('/{role}/permissions', [ApiPermissionController::class, 'assignPermissionsToRole'])
            ->name('permissions.store');
    });

    Route::middleware('role:admin')->prefix('/users')->name('users.')->group(function () {
        Route::get('/{user}/permissions', [ApiRolesController::class, 'showPermissionsToUser'])
            ->name('permissions');
        Route::post('/{user}/permissions', [ApiPermissionController::class, 'assignPermissionsToUser'])
            ->name('permissions.store');
        Route::get('/{user}/roles', [ApiPermissionController::class, 'showRolesToUser'])
            ->name('roles.store');
        Route::post('/{user}/roles', [ApiPermissionController::class, 'assignRolesToUser'])
            ->name('roles.store');
    });

    Route::middleware('role:admin')->prefix('/identity')->name('identity.')->group(function () {
        Route::get('/role/{role}', [ApiRolesController::class, 'roleIdentity'])
            ->name('role');
        Route::get('/user/{user}', [ApiRolesController::class, 'userIdentity'])
            ->name('user');
    });
});
