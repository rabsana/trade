<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolOrderTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbol_order_trades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('taker_order_id')->constrained('symbol_orders')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('maker_order_id')->constrained('symbol_orders')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->decimal('taker_qty', 40, 20)->default('0');
            $table->decimal('maker_qty', 40, 20)->default('0');
            $table->decimal('taker_price', 40, 20)->default('0');
            $table->decimal('maker_price', 40, 20)->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('symbol_order_trades');
    }
}
