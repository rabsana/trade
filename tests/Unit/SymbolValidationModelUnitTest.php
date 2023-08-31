<?php

namespace Rabsana\Trade\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Rabsana\Trade\Models\Symbol;
use Rabsana\Trade\Models\SymbolValidation;
use Rabsana\Trade\Tests\TestCase;

class SymbolValidationModelUnitTest extends TestCase
{
    use DatabaseMigrations;

    public function test_a_validation_belongs_to_a_symbol_relation()
    {
        $this->assertInstanceOf(Symbol::class, SymbolValidation::first()->symbol()->first());
    }
}
