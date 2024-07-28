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

Route::apiResource('supplier', SupplierController::class);
Route::apiResource('storage', WarehouseStorageController::class);
Route::apiResource('material', MaterialController::class);

Route::prefix('purchase')
    // ->middleware('auth:sanctum')
    ->controller(PurchaseController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'store');
        // Route::post('/{id}/approval', 'approval'); 
        // Route::post('/{id}', 'delete');
    });


Route::prefix('warehouse-material')
    // ->middleware('auth:sanctum')
    ->controller(WarehouseMaterialController::class)
    ->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/', 'update');
        // Route::post('/{id}/approval', 'approval'); 
        // Route::post('/{id}', 'delete');
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

Route::post('/purchase/{id}/process', [PurchaseController::class, 'statusToProcessed']);
Route::post('/purchase/{id}/approval', [PurchaseController::class, 'approval']);
Route::delete('/purchases/{id}', [PurchaseController::class, 'destroy']);

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
