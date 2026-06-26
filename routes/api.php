<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\MaterialCategoryController;
use App\Http\Controllers\Api\MaterialStockController;
use App\Http\Controllers\Api\MeasurementController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\OrderItemController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductionTaskController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function (): void {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::middleware(['auth:api', 'jwt.blacklist', 'rate.limit'])->group(function (): void {

    Route::prefix('auth')->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    // Users — hanya manager & admin
    Route::middleware('role:manager|admin')->group(function (): void {
        Route::apiResource('users', UserController::class);
    });

    // Customers — manager full, admin no delete, sales view only
    Route::middleware('role:manager')->group(function (): void {
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy']);
    });
    Route::middleware('role:manager|admin|sales')->group(function (): void {
        Route::apiResource('customers', CustomerController::class)->except(['destroy']);
    });

    // Payments — manager full, admin no delete, sales view only
    Route::middleware('role:manager')->group(function (): void {
        Route::delete('payments/{payment}', [PaymentController::class, 'destroy']);
    });
    Route::middleware('role:manager|admin|sales')->group(function (): void {
        Route::apiResource('payments', PaymentController::class)->except(['destroy']);
    });

    // Operational — manager & admin full, sales create/edit/view, tailor & production view only
    Route::middleware('role:manager|admin|sales|tailor|production')->group(function (): void {
        Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
        Route::apiResource('products', ProductController::class)->only(['index', 'show']);
        Route::apiResource('orders', OrderController::class)->only(['index', 'show']);
        Route::apiResource('order-items', OrderItemController::class)->only(['index', 'show']);
    });

    Route::middleware('role:manager|admin|sales')->group(function (): void {
        Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
        Route::apiResource('products', ProductController::class)->except(['index', 'show']);
        Route::apiResource('orders', OrderController::class)->except(['index', 'show']);
        Route::apiResource('order-items', OrderItemController::class)->except(['index', 'show']);
    });

    // Measurements — manager, admin, sales, tailor
    Route::middleware('role:manager|admin|sales|tailor')->group(function (): void {
        Route::apiResource('measurements', MeasurementController::class);
    });

    // Production tasks — manager, admin, production, tailor
    Route::middleware('role:manager|admin|production|tailor')->group(function (): void {
        Route::apiResource('production-tasks', ProductionTaskController::class);
    });

    // Material & supplier — manager, admin, production
    Route::middleware('role:manager|admin|production')->group(function (): void {
        Route::apiResource('suppliers', SupplierController::class);
        Route::apiResource('material-categories', MaterialCategoryController::class);
        Route::apiResource('material-stocks', MaterialStockController::class);
    });
});
