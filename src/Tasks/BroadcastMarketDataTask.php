<?php

namespace Rabsana\Trade\Tasks;

use Exception;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Events\OrderPrivateBroadcastEvent;
use Rabsana\Trade\Http\Controllers\Api\ApiMarketController;
use Rabsana\Trade\Http\Controllers\Api\ApiSymbolController;
use Rabsana\Trade\Models\SymbolOrder;

class BroadcastMarketDataTask
{
    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function run($symbolOrder, $type)
    {
        try {
            $orders = $symbolOrder;
            $orders = collect($orders)->groupBy('pair')->all();

            foreach ($orders as $pair => $order) {

                $orderBook = (new ApiMarketController())->orderBook(request(), $pair);

                if ($type == 'filled') {

                    $symbolInfo = (array) (new ApiSymbolController())->info($pair)->toResponse(request())->getData()->data;

                    $latestMatchOrders = collect((new ApiMarketController())->latestOrders(request(), $pair)->toResponse(request())->getData()->data)->map(function ($item, $key) {
                        return (array) $item;
                    })
                        ->values()
                        ->all();
                }
                event(new OrderPrivateBroadcastEvent(
                    "market.channel." . strtolower($pair), 
                    'order-book', 
                    [
                        'taker-order-book'          => $orderBook->original['data']['takers'],
                        'maker-order-book'          => $orderBook->original['data']['makers'],
                    ]
                ));

              
                if (isset($symbolInfo)) {
                    event(new OrderPrivateBroadcastEvent(
                        "market.channel-" . strtolower($pair), 
                        'symbol-info',
                        $symbolInfo
                    ));
                }

                if (isset($latestMatchOrders)) {
                    event(new OrderPrivateBroadcastEvent(
                        "market.channel." . strtolower($pair), 
                        'last-matched-orders',
                        $latestMatchOrders
                    ));
                }


                // 
            }
        } catch (Exception $e) {

            Log::debug("rabsana-trade-error-broadcast-market-data: " . $e);

            // 
        }
    }
}
