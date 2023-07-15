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
            ->middleware('role_or_permission:api.index.cart|admin')
            ->name('index');
        Route::put('/{cartItem}/setAmount', [ApiCartController::class, 'setAmount'])
            ->middleware('role_or_permission:api.setAmount.cart|admin')
            ->name('setAmount');
        Route::post('/{product}/store', [ApiCartController::class, 'store'])
            ->middleware('role_or_permission:api.store.cart|admin')
            ->name('store');
        Route::delete('/{cartItem}/delete', [ApiCartController::class, 'destroy'])
            ->middleware('role_or_permission:api.destroy.cart|admin')
            ->name('destroy');
        Route::put('/{cartItem}/changeState', [ApiCartController::class, 'changeState'])
            ->middleware('role_or_permission:api.changeState.cart|admin')
            ->name('update.state.saved');
        Route::get('/total', [ApiCartController::class, 'total'])
            ->middleware('role_or_permission:api.total.cart|admin')
            ->name('total');
    });

    Route::prefix('/order')->name('order.')->group(function () {
        Route::get('/', [ApiOrderController::class, 'index'])
            ->middleware('role_or_permission:api.order.index|admin')
            ->name('index');
        Route::get('/{order}/show', [ApiOrderController::class, 'show'])
            ->middleware('role_or_permission:api.order.show|admin')
            ->name('show');
        Route::post('/create', [ApiOrderController::class, 'store'])
            ->middleware('role_or_permission:api.order.store|admin')
            ->name('store');
        Route::get('/{order}/checkStatus', [ApiOrderController::class, 'checkStatus'])
            ->middleware('role_or_permission:api.order.store|admin')
            ->name('checkStatus');
        Route::post('/{order}/retry', [ApiOrderController::class, 'retry'])
            ->middleware('role_or_permission:api.order.retry|admin')
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
            ->middleware('role_or_permission:api.permissions.index|admin')
            ->name('index');
    });

    Route::middleware('role:admin')->prefix('/roles')->name('roles.')->group(function () {
        Route::delete('/{role}', [ApiRolesController::class, 'delete'])
            ->middleware('role_or_permission:api.roles.delete|admin')
            ->name('delete');
        Route::get('/{role}/permissions', [ApiRolesController::class, 'showPermissionsToRole'])
            ->middleware('role_or_permission:api.permissionsToRole.show|admin')
            ->name('permissions');
        Route::post('/store', [ApiRolesController::class, 'store'])
            ->middleware('role_or_permission:api.roles.store|admin')
            ->name('store');
        Route::get('/', [ApiRolesController::class, 'index'])
            ->middleware('role_or_permission:api.roles.index|admin')
            ->name('index');
        Route::post('/{role}/permissions', [ApiPermissionController::class, 'assignPermissionsToRole'])
            ->middleware('role_or_permission:api.roles.assignPermissions|admin')
            ->name('permissions.store');
    });

    Route::middleware('role:admin')->prefix('/users')->name('users.')->group(function () {
        Route::get('/{user}/permissions', [ApiRolesController::class, 'showPermissionsToUser'])
            ->middleware('role_or_permission:api.user.showPermissions|admin')
            ->name('permissions');
        Route::post('/{user}/permissions', [ApiPermissionController::class, 'assignPermissionsToUser'])
            ->middleware('role_or_permission:api.user.assignPermissions|admin')
            ->name('permissions.store');
        Route::get('/{user}/roles', [ApiPermissionController::class, 'showRolesToUser'])
            ->middleware('role_or_permission:api.user.showRoles|admin')
            ->name('roles.show');
        Route::post('/{user}/roles', [ApiPermissionController::class, 'assignRolesToUser'])
            ->middleware('role_or_permission:api.user.assignRoles|admin')
            ->name('roles.store');
    });

    Route::middleware('role:admin')->prefix('/identity')->name('identity.')->group(function () {
        Route::get('/role/{role}', [ApiRolesController::class, 'roleIdentity'])
            ->middleware('role_or_permission:api.role.identity|admin')
            ->name('role');
        Route::get('/user/{user}', [ApiRolesController::class, 'userIdentity'])
            ->middleware('role_or_permission:api.user.identity|admin')
            ->name('user');
    });
});
