<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbols', function (Blueprint $table) {
            $table->id();
            $table->string('base');
            $table->string('quote');
            $table->string('pair')->index()->unique();
            $table->string('base_name')->nullable();
            $table->string('quote_name')->nullable();
            $table->string('pair_name')->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('priority')->default(0);
            $table->boolean('buy_is_active')->default(1);
            $table->boolean('sell_is_active')->default(1);
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
        Schema::dropIfExists('symbols');
    }
}
