<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Rabsana\Trade\Models\Symbol;
use Rabsana\Trade\Models\SymbolOrderStatus;
use Rabsana\Trade\Models\SymbolOrderType;
use Rabsana\Trade\Models\SymbolValidation;

class SeedRabsanaTradePackageData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        // SymbolOrderTypeSeeder
        $types = [
            [
                'id'        => 1,
                'name'      => 'LIMIT'
            ]
        ];

        foreach ($types as $type) {
            SymbolOrderType::firstOrCreate($type);
        }



        // SymbolOrderStatusSeeder
        $statuses = [
            [
                'id'        => 1,
                'name'      => 'CREATED'
            ],
            [
                'id'        => 2,
                'name'      => 'FILLING'
            ],
            [
                'id'        => 3,
                'name'      => 'FILLED'
            ],
            [
                'id'        => 4,
                'name'      => 'CANCELED'
            ],
            [
                'id'        => 5,
                'name'      => 'FAILED'
            ],
        ];

        foreach ($statuses as $status) {
            SymbolOrderStatus::firstOrCreate($status);
        }

        // symbolSeeder
        $symbols = [
            [
                'id'                => 1,
                'base'              => 'BTC',
                'quote'             => 'USDT',
                'pair'              => 'BTCUSDT',
                'base_name'         => 'بیت کوین',
                'quote_name'        => 'تتر',
                'pair_name'         => 'بیت کوین / تتر',
                'description'       => '',
                'is_active'         => 1,
            ],
            [
                'id'                => 2,
                'base'              => 'ETH',
                'quote'             => 'USDT',
                'pair'              => 'ETHUSDT',
                'base_name'         => 'اتر',
                'quote_name'        => 'تتر',
                'pair_name'         => 'اتر / تتر',
                'description'       => '',
                'is_active'         => 1,
            ],
            [
                'id'                => 3,
                'base'              => 'BNB',
                'quote'             => 'USDT',
                'pair'              => 'BNBUSDT',
                'base_name'         => 'بایننس کوین',
                'quote_name'        => 'تتر',
                'pair_name'         => 'بایننس کوین / تتر',
                'description'       => '',
                'is_active'         => 1,
            ],
        ];

        foreach ($symbols as $symbol) {
            if (empty(Symbol::pair($symbol['pair'])->first())) {
                Symbol::create($symbol);
            }
        }


        // PivotSymbolOrderTypeSeeder
        foreach (Symbol::all() as $symbol) {
            DB::table('symbol_symbol_order_type')->insert([
                'symbol_id'             => $symbol->id,
                'symbol_order_type_id'  => 1
            ]);
        }

        // symbolValidationSeeder
        SymbolValidation::insert([
            [
                'symbol_id'                     => 1,
                'min_qty'                       => 0.00000100,
                'max_qty'                       => 9000.00000000,
                'scale_qty'                     => 6,
                'min_price'                     => 0.01000000,
                'max_price'                     => 1000000.00000000,
                'scale_price'                   => 2,
                'min_notional'                  => 10,
                'max_notional'                  => 1000000,
                'scale_notional'                => 2,
                'percent_order_price_up'        => 5,
                'percent_order_price_down'      => 5,
                'percent_order_price_minute'    => 15,
                'created_at'                    => now(),
            ],
            [
                'symbol_id'                     => 2,
                'min_qty'                       => 0.0000100,
                'max_qty'                       => 9000.00000000,
                'scale_qty'                     => 5,
                'min_price'                     => 0.01000000,
                'max_price'                     => 1000000.00000000,
                'scale_price'                   => 2,
                'min_notional'                  => 10,
                'max_notional'                  => 1000000,
                'scale_notional'                => 2,
                'percent_order_price_up'        => 5,
                'percent_order_price_down'      => 5,
                'percent_order_price_minute'    => 15,
                'created_at'                    => now(),
            ],
            [
                'symbol_id'                     => 3,
                'min_qty'                       => 0.000100,
                'max_qty'                       => 9000.00000000,
                'scale_qty'                     => 4,
                'min_price'                     => 0.01000000,
                'max_price'                     => 1000000.00000000,
                'scale_price'                   => 2,
                'min_notional'                  => 10,
                'max_notional'                  => 1000000,
                'scale_notional'                => 2,
                'percent_order_price_up'        => 5,
                'percent_order_price_down'      => 5,
                'percent_order_price_minute'    => 15,
                'created_at'                    => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
