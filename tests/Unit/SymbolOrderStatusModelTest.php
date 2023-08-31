<?php

namespace Rabsana\Trade\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Models\SymbolOrderStatus;
use Rabsana\Trade\Tests\TestCase;

class SymbolOrderStatusModelTest extends TestCase
{
    use DatabaseMigrations;

    public function test_scope_name()
    {
        $this->assertEquals(!empty(SymbolOrderStatus::name('CREATED')->first()), true);
        $this->assertEquals(!empty(SymbolOrderStatus::name('created')->first()), true);
        $this->assertEquals(empty(SymbolOrderStatus::name('FOBAR')->first()), true);
    }

    public function test_name_lower_case_attribute_is_lower_case()
    {
        $this->assertEquals(SymbolOrderStatus::first()->name_lower_case, 'created');
    }

    public function test_name_attribute_is_upper_case()
    {
        $this->assertEquals(SymbolOrderStatus::first()->name, 'CREATED');
    }

    public function test_name_translated_attribute()
    {
        $this->assertEquals(SymbolOrderStatus::first()->name_translated, 'created');
    }

    public function test_a_status_has_many_orders_relation()
    {
        SymbolOrder::create([
            'symbol_order_status_id'    => 1,
            'symbol_order_type_id'      => 1
        ]);

        $this->assertInstanceOf(SymbolOrder::class, SymbolOrderStatus::first()->orders()->first());
    }
}
