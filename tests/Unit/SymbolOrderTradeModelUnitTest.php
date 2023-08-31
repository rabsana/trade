<?php

namespace Rabsana\Trade\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Models\SymbolOrderTrade;
use Rabsana\Trade\Tests\TestCase;

class SymbolOrderTradeModelUnitTest extends TestCase
{
    use DatabaseMigrations;

    public $buyOrder;
    public $sellOrder;
    public $trade;

    public function setUp(): void
    {
        parent::setUp();

        $buyOrderId = SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1,
            'base'                      => 'BTC',
            'quote'                     => 'USDT',
            'pair'                      => 'BTCUSDT',
            'side'                      => 'BUY',
            'token'                     => NULL

        ])->id;

        $sellOrderId = SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1,
            'base'                      => 'BTC',
            'quote'                     => 'USDT',
            'pair'                      => 'BTCUSDT',
            'side'                      => 'SELL',
            'token'                     => NULL

        ])->id;

        $tradeId = SymbolOrderTrade::create([
            'taker_order_id'            => $buyOrderId,
            'maker_order_id'            => $sellOrderId
        ])->id;

        $this->buyOrder = SymbolOrder::find($buyOrderId);
        $this->sellOrder = SymbolOrder::find($sellOrderId);
        $this->trade = SymbolOrderTrade::find($tradeId);
    }

    public function test_a_trade_belongs_to_a_order_in_taker_relation()
    {
        $this->assertInstanceOf(SymbolOrder::class, $this->trade->taker()->first());
    }

    public function test_a_trade_belongs_to_a_order_in_maker_relation()
    {
        $this->assertInstanceOf(SymbolOrder::class, $this->trade->maker()->first());
    }
}
