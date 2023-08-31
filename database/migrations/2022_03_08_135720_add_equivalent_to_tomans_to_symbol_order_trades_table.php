<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEquivalentToTomansToSymbolOrderTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symbol_order_trades', function (Blueprint $table) {
            $table->unsignedBigInteger('equivalent_to_tomans')->default(0);
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

            $table->dropColumn('equivalent_to_tomans');
        });
    }
}
