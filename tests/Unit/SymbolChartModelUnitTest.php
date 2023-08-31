<?php

namespace Rabsana\Trade\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Rabsana\Trade\Models\Symbol;
use Rabsana\Trade\Models\SymbolChart;
use Rabsana\Trade\Tests\TestCase;

class SymbolChartModelUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_Chart_belongs_to_a_symbol_relation()
    {
        SymbolChart::create([
            'symbol_id' => 1
        ]);
        $this->assertInstanceOf(Symbol::class, SymbolChart::first()->symbol()->first());
    }
}
