<?php

namespace Rabsana\Trade\Actions;

use Exception;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Events\OrderCreatedEvent;
use Rabsana\Trade\Tasks\CreateUserOrderTask;
use Rabsana\Trade\Tasks\BroadcastMarketDataTask;
use Rabsana\Trade\Tasks\SendOrderWithWebsocketTask;

class OrderCreatedAction
{
    public $name = '';
    public $takerFee = 0;
    public $makerFee = 0;

    public function run(SymbolOrder $symbolOrder)
    {
        try {
            event(new OrderCreatedEvent($symbolOrder));
            app(CreateUserOrderTask::class)->run($symbolOrder);
            app(BroadcastMarketDataTask::class)->run($symbolOrder, 'created');
            app(SendOrderWithWebsocketTask::class)->run($symbolOrder, 'created');
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
}
