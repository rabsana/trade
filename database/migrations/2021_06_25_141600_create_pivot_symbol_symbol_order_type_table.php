<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotSymbolSymbolOrderTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbol_symbol_order_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('symbol_order_type_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
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
        Schema::dropIfExists('symbol_symbol_order_type');
    }
}
