<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Events\OrderPrivateBroadcastEvent;
use Illuminate\Support\Facades\Lang;

class CreateUserOrderTask
{
    public function run($symbolOrder)
    {
        event(new OrderPrivateBroadcastEvent(
            "market.user.channel.".$symbolOrder->orderable_id, 
            'market-user-messages', 
            [
                'type' => 'created',
                'message' => Lang::get("trade::symbolOrder.yourOrderCreatedSuccessfully",[
                    'type' => Lang::get("trade::symbolOrder." . $symbolOrder->side),
                    'pair' => $symbolOrder->pair,
                    'count' => $symbolOrder->base_qty
                ])
            ]
        ));

       
    }
}
