<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEquivalentToTomansToSymbolOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symbol_orders', function (Blueprint $table) {
            $table->string('equivalent_to_tomans')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('symbol_orders', function (Blueprint $table) {
            $table->dropColumn('equivalent_to_tomans');
        });
    }
}
