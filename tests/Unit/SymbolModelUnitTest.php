<?php

namespace Rabsana\Trade\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Rabsana\Trade\Models\Symbol;
use Rabsana\Trade\Models\SymbolChart;
use Rabsana\Trade\Models\SymbolOrderType;
use Rabsana\Trade\Models\SymbolValidation;
use Rabsana\Trade\Tests\TestCase;

class SymbolModelUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function test_scope_base()
    {
        $this->assertEquals(!empty(Symbol::base('BTC')->first()), true);
        $this->assertEquals(!empty(Symbol::base('btc')->first()), true);
        $this->assertEquals(empty(Symbol::base('FOBAR')->first()), true);
    }

    public function test_scope_quote()
    {
        $this->assertEquals(!empty(Symbol::quote('USDT')->first()), true);
        $this->assertEquals(!empty(Symbol::quote('usdt')->first()), true);
        $this->assertEquals(empty(Symbol::quote('FOBAR')->first()), true);
    }

    public function test_scope_pair()
    {
        $this->assertEquals(!empty(Symbol::pair('BTCUSDT')->first()), true);
        $this->assertEquals(!empty(Symbol::pair('btcusdt')->first()), true);
        $this->assertEquals(empty(Symbol::pair('FOBAR')->first()), true);
    }

    public function test_scope_buy_is_active()
    {
        $this->assertEquals(!empty(Symbol::buyIsActive(1)->first()), true);
        $this->assertEquals(empty(Symbol::buyIsActive(0)->first()), true);
    }

    public function test_scope_sell_is_active()
    {
        $this->assertEquals(!empty(Symbol::sellIsActive(1)->first()), true);
        $this->assertEquals(empty(Symbol::sellIsActive(0)->first()), true);
    }

    public function test_base_lower_case_attribute_is_lower_case()
    {
        $this->assertEquals(Symbol::first()->base_lower_case, 'btc');
    }

    public function test_base_attribute_is_upper_case()
    {
        $this->assertEquals(Symbol::first()->base, 'BTC');
    }

    public function test_quote_lower_case_attribute_is_lower_case()
    {
        $this->assertEquals(Symbol::first()->quote_lower_case, 'usdt');
    }

    public function test_quote_attribute_is_upper_case()
    {
        $this->assertEquals(Symbol::first()->quote, 'USDT');
    }

    public function test_pair_lower_case_attribute_is_lower_case()
    {
        $this->assertEquals(Symbol::first()->pair_lower_case, 'btcusdt');
    }

    public function test_pair_attribute_is_upper_case()
    {
        $this->assertEquals(Symbol::first()->pair, 'BTCUSDT');
    }

    public function test_base_attribute_will_store_in_upper_case()
    {
        Symbol::create([
            'base'  => 'fo',
            'quote' => 'bar',
            'pair' => 'fobar'
        ]);

        $symbol = Symbol::pair('fobar')->first();

        $this->assertEquals($symbol->getRawOriginal('base'), 'FO');
    }

    public function test_quote_attribute_will_store_in_upper_case()
    {
        Symbol::create([
            'base'  => 'fo',
            'quote' => 'bar',
            'pair' => 'fobar'
        ]);

        $symbol = Symbol::pair('fobar')->first();

        $this->assertEquals($symbol->getRawOriginal('quote'), 'BAR');
    }

    public function test_pair_attribute_will_store_in_upper_case()
    {
        Symbol::create([
            'base'  => 'fo',
            'quote' => 'bar',
            'pair' => 'fobar'
        ]);

        $symbol = Symbol::pair('fobar')->first();

        $this->assertEquals($symbol->getRawOriginal('pair'), 'FOBAR');
    }

    public function test_base_and_quote_media_attribute()
    {

        // test if there is a symbol that does not have any image, the media accessor will return "not-found.png" not empty string
        Symbol::create([
            'base'  => 'fo',
            'quote' => 'bar',
            'pair' => 'fobar'
        ]);

        $symbol = Symbol::pair('fobar')->first();

        $pathNotDefined = false;
        foreach ($symbol->base_media['image'] as $type) {
            foreach ($type as $path) {
                if (empty($path)) {
                    $pathNotDefined = true;
                    break;
                }
            }
        }

        $this->assertEquals($pathNotDefined, false);
    }

    public function test_a_symbol_belongs_to_many_types_relation()
    {
        $this->assertInstanceOf(SymbolOrderType::class, Symbol::first()->types()->first());
    }

    public function test_a_symbol_has_one_validation_relation()
    {
        $this->assertInstanceOf(SymbolValidation::class, Symbol::first()->validation()->first());
    }

    public function test_validation_relation_return_a_default_data()
    {
        // create a new symbol without any validation
        Symbol::create([
            'base'  => 'fo',
            'quote' => 'bar',
            'pair' => 'fobar'
        ]);

        $symbol = Symbol::pair('fobar')->with('validation')->first();
        $this->assertInstanceOf(SymbolValidation::class, $symbol->validation);
    }

    public function test_a_symbol_has_many_charts_relation()
    {
        $symbol = Symbol::first();

        SymbolChart::create([
            'symbol_id' => $symbol->id
        ]);

        $this->assertInstanceOf(SymbolChart::class, $symbol->charts()->first());
    }
}
