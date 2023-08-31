<?php

use Illuminate\Support\Facades\Route;
use Rabsana\Trade\Http\Controllers\AdminApi\AdminApiCommissionController;
use Rabsana\Trade\Http\Controllers\AdminApi\AdminApiCommonController;
use Rabsana\Trade\Http\Controllers\AdminApi\AdminApiOrderController;
use Rabsana\Trade\Http\Controllers\AdminApi\AdminApiSymbolController;
use Rabsana\Trade\Http\Controllers\AdminApi\AdminApiSymbolOrderStatusController;
use Rabsana\Trade\Http\Controllers\AdminApi\AdminApiSymbolOrderTypeController;


Route::prefix("admin-api")->name('admin-api.')->group(function () {

    Route::prefix('v1')->name('v1.')->group(function () {


        // public routes
        Route::middleware(config('rabsana-trade.adminApiMiddlewares.public', []))->group(function () {
        });


        // private routes
        Route::middleware(config('rabsana-trade.adminApiMiddlewares.private', []))->group(function () {

            // symbols
            Route::resource("symbols", AdminApiSymbolController::class);
            Route::post("symbols/{symbol}/change/priority", [AdminApiSymbolController::class, 'changePriority'])->name("symbols.change.priority");

            Route::get("order-types", [AdminApiSymbolOrderTypeController::class, 'index'])->name('order-types.index');
            Route::get("order-statuses", [AdminApiSymbolOrderStatusController::class, 'index'])->name('order-statuses.index');

            Route::resource("commissions", AdminApiCommissionController::class);

            Route::prefix('common')->name('common.')->group(function () {

                Route::get("symbols/quotes", [AdminApiCommonController::class, 'symbolQuotes'])->name('symbols.quotes');
                Route::get("commissions/properties", [AdminApiCommonController::class, 'commissionProperties'])->name('commissions.properties');
                Route::get("commissions/operators", [AdminApiCommonController::class, 'commissionOperators'])->name('commissions.operators');

                // 
            });

            // order
            Route::apiResource("orders", AdminApiOrderController::class);
        });


        // 

    });
});
