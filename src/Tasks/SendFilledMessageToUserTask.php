<?php

namespace Rabsana\Trade\Tasks;

use Exception;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Events\OrderPrivateBroadcastEvent;
use Rabsana\Trade\Models\SymbolOrder;

class SendFilledMessageToUserTask
{

    public function run($symbolOrders)
    {
        try {

            $orders = $symbolOrders;
            foreach ($orders as $order) {
                event(new OrderPrivateBroadcastEvent(
                    "market.user.channel.{$order->orderable_id}", 
                    'market-user-messages', 
                    array(
                        'type' => 'filled',
                        'message' => " سفارش {$order->side_translated} {$order->original_base_qty_prettified} {$order->base_name} با موفقیت انجام شد "
                    )
                ));
            }

            // 
        } catch (Exception $e) {
            Log::debug("send-filled-message-to-user-listener: " . $e);
        }
    }
}
