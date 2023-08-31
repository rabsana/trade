<?php

namespace Rabsana\Trade\Tasks;

use Exception;
use Rabsana\Trade\Events\OrderPrivateBroadcastEvent;
use Rabsana\Trade\Models\SymbolOrder;

class SendOrderWithWebsocketTask
{

    public function run(SymbolOrder $symbolOrder, $type)
    {
        try {
            $data = [];
            if($type == 'deleted') {
                $data = [
                    'id' => $symbolOrder->id
                ];
            } else {
                $data = [
                    'id' => (int) $symbolOrder->id,
                    'status_id' => (int) $symbolOrder->symbol_order_status_id,
                    'type_id' => (int) $symbolOrder->symbol_order_type_id,
                    'side' => $symbolOrder->side,
                    'side_translated' => $symbolOrder->side_translated,
                    'base_qty' => $symbolOrder->base_qty,
                    'base_qty_prettified' => $symbolOrder->base_qty_prettified,
                    'quote_qty' => $symbolOrder->quote_qty,
                    'quote_qty_prettified' => $symbolOrder->quote_qty_prettified,
                    'price' => $symbolOrder->price,
                    'price_prettified' => $symbolOrder->price_prettified,
                    'base' => $symbolOrder->base,
                    'quote' => $symbolOrder->quote,
                    'pair_name' => $symbolOrder->pair_name,
                    'filled_percent' => $symbolOrder->filled_percent,
                    'cancelable' => $symbolOrder->cancelable
                ];
            }
           
            event(new OrderPrivateBroadcastEvent(
                'market.user.channel.'. $symbolOrder->orderable_id,
                'user-order',
                [
                    $type => $data
                ]
            ));
        } catch (Exception $e) {
            
        }
    }
}
