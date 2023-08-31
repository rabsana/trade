<?php

use Illuminate\Database\Migrations\Migration;
use Rabsana\Trade\Models\SymbolOrderType;

class AddMarketToSymbolOrderTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        SymbolOrderType::create([
            'id'    => 2,
            'name'  => 'MARKET'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        SymbolOrderType::whereName('MARKET')->delete();
    }
}
