<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolValidationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbol_validation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->decimal('min_qty', 40, 20)->default('0.00000001');
            $table->decimal('max_qty', 40, 20)->default('99999999');
            $table->unsignedInteger('scale_qty')->default('8');
            $table->decimal('min_price', 40, 20)->default('0.00000001');
            $table->decimal('max_price', 40, 20)->default('99999999');
            $table->unsignedInteger('scale_price')->default('8');
            $table->decimal('min_notional', 40, 20)->default('0.00000001');
            $table->decimal('max_notional', 40, 20)->default('99999999');
            $table->unsignedInteger('scale_notional')->default('8');
            $table->decimal('percent_order_price_up', 40, 20)->default('5.00000000');
            $table->decimal('percent_order_price_down', 40, 20)->default('5.00000000');
            $table->unsignedInteger('percent_order_price_minute')->default('5');
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
        Schema::dropIfExists('symbol_validation');
    }
}
