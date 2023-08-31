<?php

namespace Rabsana\Trade\Actions;

use Exception;
use Rabsana\Trade\Events\OrdersHaveFilledEvent;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Tasks\UpdateSymbolInfoTask;
use Rabsana\Trade\Tasks\BroadcastMarketDataTask;
use Rabsana\Trade\Tasks\SendFilledMessageToUserTask;

class OrderHaveFilledAction
{
    public $name = '';
    public $takerFee = 0;
    public $makerFee = 0;

    public function run($symbolOrders)
    {
        try {
            event(new OrdersHaveFilledEvent($symbolOrders));
            $symbolOrders = SymbolOrder::whereIn('id', collect($symbolOrders)->pluck('id')->toArray())
            ->get();
            app(UpdateSymbolInfoTask::class)->run();
            app(BroadcastMarketDataTask::class)->run($symbolOrders, 'filled');
            app(SendFilledMessageToUserTask::class)->run($symbolOrders);
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
       
    }

   
}
