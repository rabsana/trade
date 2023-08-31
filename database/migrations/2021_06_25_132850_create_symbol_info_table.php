<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbol_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->decimal('price', 40, 20)->default(0);
            $table->decimal('last_day_high', 40, 20)->default(0);
            $table->decimal('last_day_low', 40, 20)->default(0);
            $table->decimal('last_day_base_volume', 40, 20)->default(0);
            $table->decimal('today_high', 40, 20)->default(0);
            $table->decimal('today_low', 40, 20)->default(0);
            $table->decimal('today_base_volume', 40, 20)->default(0);
            $table->decimal('change_percent', 40, 20)->default(0);
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
        Schema::dropIfExists('symbol_info');
    }
}
