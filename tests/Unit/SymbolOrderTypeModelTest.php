<?php

namespace Rabsana\Trade\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Rabsana\Trade\Models\Symbol;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Models\SymbolOrderType;
use Rabsana\Trade\Tests\TestCase;

class SymbolOrderTypeModelTest extends TestCase
{
    use DatabaseMigrations;

    public function test_scope_name()
    {
        $this->assertEquals(!empty(SymbolOrderType::name('LIMIT')->first()), true);
        $this->assertEquals(!empty(SymbolOrderType::name('limit')->first()), true);
        $this->assertEquals(empty(SymbolOrderType::name('FOBAR')->first()), true);
    }

    public function test_name_lower_case_attribute_is_lower_case()
    {
        $this->assertEquals(SymbolOrderType::first()->name_lower_case, 'limit');
    }

    public function test_name_attribute_is_upper_case()
    {
        $this->assertEquals(SymbolOrderType::first()->name, 'LIMIT');
    }

    public function test_name_translated_attribute()
    {
        $this->assertEquals(SymbolOrderType::first()->name_translated, 'Limit Order');
    }

    public function test_a_type_belongs_to_many_symbols_relation()
    {
        $this->assertInstanceOf(Symbol::class, SymbolOrderType::first()->symbols()->first());
    }

    public function test_a_type_has_many_orders_relation()
    {
        SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1
        ]);

        $this->assertInstanceOf(SymbolOrder::class, SymbolOrderType::first()->orders()->first());
    }
}
