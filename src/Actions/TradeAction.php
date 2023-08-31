<?php

namespace Rabsana\Trade\Actions;

use Exception;
use Rabsana\Trade\Events\TradeEvent;
use Rabsana\Core\Support\Facades\Math;
use Rabsana\Trade\Models\SymbolOrderTrade;
use Rabsana\Trade\Events\OrderPrivateBroadcastEvent;


class TradeAction
{
    public $name = '';
    public $takerFee = 0;
    public $makerFee = 0;

    public function run(SymbolOrderTrade $symbolOrdertrade)
    {
        try {
            // ChangeBalcneOf Buyer and Seller
            event(new TradeEvent($symbolOrdertrade));

            // send trade to websocket
            event(new OrderPrivateBroadcastEvent(
                "market.channel." . strtolower($symbolOrdertrade->taker->pair), 
                'last-trade', 
                [
                    'base' => $symbolOrdertrade->taker->base,
                    'qty' => $symbolOrdertrade->taker_qty,
                    'price' => $symbolOrdertrade->maker_price,
                    'total_price' => Math::multiply((float) $symbolOrdertrade->taker_qty, (float) $symbolOrdertrade->maker_price),
                    'type'  => $symbolOrdertrade->type,
                    'kind'  => $symbolOrdertrade->type == 'sell' ? 'maker' : 'taker'
                ]
            ));
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
}
