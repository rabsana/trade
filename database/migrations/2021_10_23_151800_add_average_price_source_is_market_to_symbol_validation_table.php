<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAveragePriceSourceIsMarketToSymbolValidationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symbol_validation', function (Blueprint $table) {
            $table->boolean('average_price_source_is_market')->default(1)->after('percent_order_price_minute');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('symbol_validation', function (Blueprint $table) {
            $table->dropColumn('average_price_source_is_market');
        });
    }
}
