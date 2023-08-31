<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolChartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbol_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->string('candle')->default(1);
            $table->string('open_time')->default(time());
            $table->decimal('open', 40, 20)->default(0);
            $table->decimal('high', 40, 20)->default(0);
            $table->decimal('low', 40, 20)->default(0);
            $table->decimal('close', 40, 20)->default(0);
            $table->string('close_time')->default(time());
            $table->decimal('base_volume', 40, 20)->default(0);
            $table->decimal('quote_volume', 40, 20)->default(0);
            $table->unsignedBigInteger('trade_numbers')->default(0);
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
        Schema::dropIfExists('symbol_charts');
    }
}
