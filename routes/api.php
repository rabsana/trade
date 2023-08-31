<?php

use Illuminate\Support\Facades\Route;
use Rabsana\Trade\Http\Controllers\Api\ApiCommissionController;
use Rabsana\Trade\Http\Controllers\Api\ApiMarketController;
use Rabsana\Trade\Http\Controllers\Api\ApiOrderController;
use Rabsana\Trade\Http\Controllers\Api\ApiSymbolChartController;
use Rabsana\Trade\Http\Controllers\Api\ApiSymbolController;
use Rabsana\Trade\Http\Controllers\Api\ApiSymbolOrderStatusController;
use Rabsana\Trade\Http\Controllers\Api\ApiSymbolOrderTypeController;
use Rabsana\Trade\Http\Controllers\Api\ApiTradeController;

Route::prefix("api")->name('api.')->group(function () {

    Route::prefix('v1')->name('v1.')->group(function () {

        // public routes
        Route::middleware(config('rabsana-trade.apiMiddlewares.public', []))->group(function () {


            // symbols
            Route::prefix("symbols")->name('symbols.')->group(function () {

                Route::get("/", [ApiSymbolController::class, 'index'])->name('index');
                Route::get("/info/{symbol?}", [ApiSymbolController::class, 'info'])->name('info');
                Route::get("/quotes", [ApiSymbolController::class, 'symbolQuotes'])->name('quotes');

                // 
            });


            // chart data
            Route::prefix("charts")->name('charts.')->group(function () {

                Route::get("candles", [ApiSymbolChartController::class, 'candles'])->name('candles');
                Route::get("{symbol}/{candle?}", [ApiSymbolChartController::class, 'index'])->name('index');

                // 
            });

            Route::prefix("markets")->name('markets.')->group(function () {

                Route::get("order-book/{symbol?}", [ApiMarketController::class, 'orderBook'])->name('order-book');
                Route::get("taker-order-book/{symbol?}", [ApiMarketController::class, 'takerOrderBook'])->name('taker-order-book');
                Route::get("maker-order-book/{symbol?}", [ApiMarketController::class, 'makerOrderBook'])->name('maker-order-book');

                Route::get("latest-orders/{symbol?}", [ApiMarketController::class, 'latestOrders'])->name('latest-orders');
            });

            Route::get("order-types", [ApiSymbolOrderTypeController::class, 'index'])->name('order-types.index');
            Route::get("order-statuses", [ApiSymbolOrderStatusController::class, 'index'])->name('order-statuses.index');

            // 
        });



        // orders
        Route::middleware(config('rabsana-trade.apiMiddlewares.private', []))->group(function () {

            Route::prefix("orders")->name('orders.')->group(function () {

                Route::get('/', [ApiOrderController::class, 'index'])->name('index');
                Route::get('{order}', [ApiOrderController::class, 'show'])->name('show');
                Route::post('/', [ApiOrderController::class, 'store'])->name('store');
                Route::get('{order}/cancel', [ApiOrderController::class, 'cancel'])->name('cancel');

                // 
            });

            Route::prefix("commissions")->name("commissions.")->group(function () {
                Route::get("/", [ApiCommissionController::class, 'index'])->name("index");
            });

            // 
        });


        // trades
        Route::middleware(config('rabsana-trade.apiMiddlewares.private', []))->group(function () {

            Route::prefix("trades")->name('trades.')->group(function () {

                Route::get('/' , [ApiTradeController::class , 'index'])->name('index');
                Route::get('/last/{symbol}' , [ApiTradeController::class , 'last'])->name('last');
            });


            //
        });


    });
});
