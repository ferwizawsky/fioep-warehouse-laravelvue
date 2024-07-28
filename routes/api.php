<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseMaterialController;
use App\Http\Controllers\WarehouseStorageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('logout', 'logout');
        Route::middleware('auth:sanctum')->get('profile', 'profile');
    });


Route::get('/purchase-export', [PurchaseController::class, 'export']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('supplier', SupplierController::class);
    Route::apiResource('storage', WarehouseStorageController::class);
    Route::apiResource('material', MaterialController::class);



    Route::middleware('role:2|1|3')->group(function () {
        Route::prefix('purchase')
            ->controller(PurchaseController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{id}', 'show');
                Route::post('/', 'store');
            });
        Route::post('/purchase/{id}/process', [PurchaseController::class, 'statusToProcessed']);
        Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy'])->middleware('role:2');
        Route::post('/purchase/{id}/approval', [PurchaseController::class, 'approval'])->middleware('role:2');
    });

    Route::middleware('role:1|4')->group(function () {
        Route::prefix('warehouse-material')
            ->controller(WarehouseMaterialController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/{id}', 'show');
                Route::post('/', 'update');
            });
        Route::prefix('warehouse')
            ->controller(WarehouseController::class)
            ->group(function () {
                Route::get('/', 'index');
                Route::get('/list-processed-purchase', 'indexPurchase');
                Route::get('/{id}', 'show');
                Route::post('/', 'store');
                // Route::post('/{id}', 'delete');
            });
    });
});


Route::prefix('user')
    ->middleware('auth:sanctum')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        Route::post('/{id}/edit', 'edit');
        Route::post('/{id}/delete', 'delete');
    });

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
