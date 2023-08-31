<?php

namespace Rabsana\Trade\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class TradeEventServiceProvider extends EventServiceProvider
{
    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens()
    {
        // to pervent get "Constant expression contains invalid operations" exception I overwrote the listens method from EventServiceProvider
        return [

            'Rabsana\\Trade\\Events\\OrdersHaveFilledEvent' => array_merge(
                (array) config('rabsana-trade.ordersHaveFilledEvent', []),
                []
            ),

            'Rabsana\\Trade\\Events\\OrderCreatedEvent' =>  array_merge(
                (array) config('rabsana-trade.orderCreatedEvent', []),
                []
            ),

            'Rabsana\\Trade\\Events\\OrderCancelledEvent' =>  array_merge(
                (array) config('rabsana-trade.orderCancelledEvent', []),
                []
            ),
            'Rabsana\\Trade\\Events\\OrderPrivateBroadcastEvent' =>  array_merge(
                (array) config('rabsana-trade.orderPrivateBroadcastEvent', []),
                []
            ),
            'Rabsana\\Trade\\Events\\TradeEvent' =>  array_merge(
                (array) config('rabsana-trade.tradeEvent', []),
                []
            )
        ];
    }
}
