<?php

namespace Rabsana\Trade\Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Rabsana\Trade\Tests\TestCase;

class CRUDSymbolsFeatureTest extends TestCase
{
    use DatabaseMigrations;


    // ********** admin api endpoints **********
    public function test_a_admin_can_see_symbols_list()
    {
        $response = $this->get(route("rabsana-trade.admin-api.v1.symbols.index"));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors',
            'data'
        ]);
        $response->assertSee([
            "BTC",
            "USDT",
            "BTCUSDT"
        ]);
    }

    public function test_a_admin_can_see_a_symbol_detail()
    {
        $response = $this->get(route("rabsana-trade.admin-api.v1.symbols.show", ['symbol' => 1]));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors',
            'data'
        ]);
        $response->assertSee([
            "BTC",
            "USDT",
            "BTCUSDT"
        ]);
    }

    public function test_a_admin_can_store_a_new_symbol()
    {
        $data = [
            'base'                          => 'cake',
            'quote'                         => 'usdt',
            'pair'                          => 'cakeusdt',
            'description'                   => 'Some Description',
            'priority'                      => 10,
            'buy_is_active'                 => 1,
            'sell_is_active'                => 1,

            'min_qty'                       => '0.00000001',
            'max_qty'                       => '10.00000000',
            'scale_qty'                     => 10,
            'min_price'                     => '0.00000001',
            'max_price'                     => '10.00000000',
            'scale_price'                   => 10,
            'min_notional'                  => '0.00000001',
            'max_notional'                  => '10.00000000',
            'scale_notional'                => 10,
            'percent_order_price_up'        => '5',
            'percent_order_price_down'      => '0.2',
            'percent_order_price_minute'    => '5',

            'types'                         => [1]
        ];
        $response = $this->post(
            route("rabsana-trade.admin-api.v1.symbols.store"),
            $data,
            [
                'Accept'                => 'application/json'
            ]
        );

        $response->assertStatus(200);
        $response->assertSee($data['base']);
    }

    public function test_a_admin_can_update_a_symbol()
    {
        $data = [
            'base'                          => 'ada',
            'quote'                         => 'usdt',
            'pair'                          => 'adausdt',
            'description'                   => 'Some Description',
            'priority'                      => 10,
            'buy_is_active'                 => 1,
            'sell_is_active'                => 1,

            'min_qty'                       => '0.00000001',
            'max_qty'                       => '10.00000000',
            'scale_qty'                     => 10,
            'min_price'                     => '0.00000001',
            'max_price'                     => '10.00000000',
            'scale_price'                   => 10,
            'min_notional'                  => '0.00000001',
            'max_notional'                  => '10.00000000',
            'scale_notional'                => 10,
            'percent_order_price_up'        => '5',
            'percent_order_price_down'      => '0.2',
            'percent_order_price_minute'    => '5',

            'types'                         => [1]
        ];
        $response = $this->patch(
            route("rabsana-trade.admin-api.v1.symbols.update", ['symbol' => 1]),
            $data,
            [
                'Accept'                => 'application/json'
            ]
        );

        $response->assertStatus(200);
        $response->assertSee($data['base']);
    }


    public function test_a_admin_can_delete_a_symbol()
    {
        $response = $this->delete(
            route("rabsana-trade.admin-api.v1.symbols.destroy", ['symbol' => 1]),
            [],
            [
                'Accept'                => 'application/json'
            ]
        );
        $response->assertStatus(200);


        $response = $this->get(
            route("rabsana-trade.admin-api.v1.symbols.show", ['symbol' => 1]),
            [],
            [
                'Accept'                => 'application/json'
            ]
        );
        $response->status(404);
    }
}
