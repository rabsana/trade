<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToSymbolOrdersPartTwoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symbol_orders', function (Blueprint $table) {
            $table->timestamp('filling_at')->nullable();
            $table->timestamp('filled_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('failed_at')->nullable();
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
            $table->dropColumn('filling_at');
            $table->dropColumn('filled_at');
            $table->dropColumn('canceled_at');
            $table->dropColumn('failed_at');
        });
    }
}
