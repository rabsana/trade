<?php

namespace Rabsana\Trade\Actions;

use Exception;
use Rabsana\Trade\Helpers\Math;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Events\OrderCancelledEvent;
use Rabsana\Trade\Tasks\BroadcastMarketDataTask;
use Rabsana\Trade\Tasks\SendOrderWithWebsocketTask;
use Rabsana\Trade\Events\OrderPrivateBroadcastEvent;

class OrderCancelledAction
{
    public $name = '';
    public $takerFee = 0;
    public $makerFee = 0;

    public function run(SymbolOrder $symbolOrder, $type)
    {
        try {
            event(new OrderCancelledEvent($symbolOrder));
            app(BroadcastMarketDataTask::class)->run($symbolOrder, $type);

            app(SendOrderWithWebsocketTask::class)->run($symbolOrder, 'deleted');
            event(new OrderPrivateBroadcastEvent(
                "market.user.channel.".$symbolOrder->orderable_id, 
                'market-user-messages', 
                [
                    'type' => 'created',
                    'message' => Lang::get("trade::symbolOrder.yourOrderCanceledSuccessfully", [
                        'type' => Lang::get("trade::symbolOrder." . $symbolOrder->side),
                        'pair' => $symbolOrder->pair,
                        'count' => Math::subtract($symbolOrder->base_qty ,$symbolOrder->filled_base_qty)
                    ])
                ]
            ));
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

}
