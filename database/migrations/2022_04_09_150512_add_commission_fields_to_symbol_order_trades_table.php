<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommissionFieldsToSymbolOrderTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symbol_order_trades', function (Blueprint $table) {
            $table->decimal('base_commission', 40, 20)->default('0');
            $table->decimal('quote_commission', 40, 20)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('symbol_order_trades', function (Blueprint $table) {
            $table->dropColumn('base_commission');
            $table->dropColumn('quote_commission');
        });
    }
}
