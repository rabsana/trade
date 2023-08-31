<?php

namespace Rabsana\Trade\Tests\Unit;

use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Models\SymbolOrderStatus;
use Rabsana\Trade\Models\SymbolOrderType;
use Rabsana\Trade\Tests\TestCase;

class SymbolOrderModelUnitTest extends TestCase
{
    use DatabaseMigrations;

    public $order;

    public function setUp(): void
    {
        parent::setUp();

        $orderId = SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1,
            'base'                      => 'BTC',
            'quote'                     => 'USDT',
            'pair'                      => 'BTCUSDT',
            'side'                      => 'BUY',
            'token'                     => NULL

        ])->id;

        $this->order = SymbolOrder::find($orderId);
    }

    public function test_scope_status_id()
    {
        $this->assertEquals(!empty(SymbolOrder::statusId(1)->first()), true);
        $this->assertEquals(empty(SymbolOrder::statusId(2)->first()), true);
    }

    public function test_scope_type_id()
    {
        $this->assertEquals(!empty(SymbolOrder::typeId(1)->first()), true);
        $this->assertEquals(empty(SymbolOrder::typeId(2)->first()), true);
    }

    public function test_scope_base()
    {
        $this->assertEquals(!empty(SymbolOrder::base('BTC')->first()), true);
        $this->assertEquals(!empty(SymbolOrder::base('btc')->first()), true);
        $this->assertEquals(empty(SymbolOrder::base('FOBAR')->first()), true);
    }

    public function test_scope_quote()
    {
        $this->assertEquals(!empty(SymbolOrder::quote('USDT')->first()), true);
        $this->assertEquals(!empty(SymbolOrder::quote('usdt')->first()), true);
        $this->assertEquals(empty(SymbolOrder::quote('FOBAR')->first()), true);
    }

    public function test_scope_pair()
    {
        $this->assertEquals(!empty(SymbolOrder::pair('BTCUSDT')->first()), true);
        $this->assertEquals(!empty(SymbolOrder::pair('btcusdt')->first()), true);
        $this->assertEquals(empty(SymbolOrder::pair('FOBAR')->first()), true);
    }

    public function test_scope_side()
    {
        $this->assertEquals(!empty(SymbolOrder::side('BUY')->first()), true);
        $this->assertEquals(!empty(SymbolOrder::side('buy')->first()), true);
        $this->assertEquals(empty(SymbolOrder::side('FOBAR')->first()), true);
    }

    public function test_base_lower_case_attribute_is_lower_case()
    {
        $this->assertEquals($this->order->base_lower_case, 'btc');
    }

    public function test_base_attribute_is_upper_case()
    {
        $this->assertEquals($this->order->base, 'BTC');
    }

    public function test_quote_lower_case_attribute_is_lower_case()
    {
        $this->assertEquals($this->order->quote_lower_case, 'usdt');
    }

    public function test_quote_attribute_is_upper_case()
    {
        $this->assertEquals($this->order->quote, 'USDT');
    }

    public function test_pair_lower_case_attribute_is_lower_case()
    {
        $this->assertEquals($this->order->pair_lower_case, 'btcusdt');
    }

    public function test_pair_attribute_is_upper_case()
    {
        $this->assertEquals($this->order->pair, 'BTCUSDT');
    }

    public function test_side_lower_case_attribute_is_lower_case()
    {
        $this->assertEquals($this->order->side_lower_case, 'buy');
    }

    public function test_side_attribute_is_upper_case()
    {
        $this->assertEquals($this->order->side, 'BUY');
    }

    public function test_side_translated_attribute()
    {
        $this->assertEquals(strtolower($this->order->side_translated), 'buy');
    }


    public function test_base_attribute_will_store_in_upper_case()
    {
        SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1,
            'base'                      => 'fo',
            'quote'                     => 'bar',
            'pair'                      => 'fobar'
        ]);

        $order = SymbolOrder::pair('fobar')->first();

        $this->assertEquals($order->getRawOriginal('base'), 'FO');
    }

    public function test_quote_attribute_will_store_in_upper_case()
    {
        SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1,
            'base'                      => 'fo',
            'quote'                     => 'bar',
            'pair'                      => 'fobar'
        ]);

        $order = SymbolOrder::pair('fobar')->first();

        $this->assertEquals($order->getRawOriginal('quote'), 'BAR');
    }

    public function test_pair_attribute_will_store_in_upper_case()
    {
        SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1,
            'base'                      => 'fo',
            'quote'                     => 'bar',
            'pair'                      => 'fobar'
        ]);

        $order = SymbolOrder::pair('fobar')->first();

        $this->assertEquals($order->getRawOriginal('pair'), 'FOBAR');
    }

    public function test_side_attribute_will_store_in_upper_case()
    {
        SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1,
            'base'                      => 'fo',
            'quote'                     => 'bar',
            'pair'                      => 'fobar',
            'side'                      => 'sell'
        ]);

        $order = SymbolOrder::pair('fobar')->first();

        $this->assertEquals($order->getRawOriginal('side'), 'SELL');
    }

    public function test_order_token_will_store_unique()
    {
        // create an order with duplicate token
        $orderId = SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1,
            'base'                      => 'BTC',
            'quote'                     => 'USDT',
            'pair'                      => 'BTCUSDT',
            'side'                      => 'BUY',
            'token'                     => $this->order->token
        ])->id;

        $this->assertNotEquals(SymbolOrder::find($orderId)->token, $this->order->token);
    }

    public function test_base_and_quote_media_attribute()
    {

        // test if there is a symbol that does not have any image, the media accessor will return "not-found.png" not empty string
        SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1,
            'base'                      => 'fo',
            'quote'                     => 'bar',
            'pair'                      => 'fobar'
        ]);

        $order = SymbolOrder::pair('fobar')->first();

        $pathNotDefined = false;
        foreach ($order->base_media['image'] as $type) {
            foreach ($type as $path) {
                if (empty($path)) {
                    $pathNotDefined = true;
                    break;
                }
            }
        }

        $this->assertEquals($pathNotDefined, false);
    }


    public function test_a_order_belongs_to_a_status_relation()
    {
        $this->assertInstanceOf(SymbolOrderStatus::class, $this->order->status()->first());
    }

    public function test_a_order_belongs_to_a_type_relation()
    {
        $this->assertInstanceOf(SymbolOrderType::class, $this->order->type()->first());
    }

    public function test_a_order_can_be_morphed_to()
    {
        $this->assertInstanceOf(MorphTo::class, $this->order->orderable());
    }
}
