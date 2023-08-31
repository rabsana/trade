<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSymbolOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbol_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('symbol_order_status_id')->constrained()->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->foreignId('symbol_order_type_id')->constrained()->onDelete('RESTRICT')->onUpdate('RESTRICT');
            $table->nullableMorphs('orderable');
            $table->string('base')->nullable();
            $table->string('quote')->nullable();
            $table->string('pair')->nullable();
            $table->string('base_name')->nullable();
            $table->string('quote_name')->nullable();
            $table->string('pair_name')->nullable();
            $table->string('side')->nullable();
            $table->decimal('base_qty', 40, 20)->default('0');
            $table->decimal('quote_qty', 40, 20)->default('0');
            $table->decimal('filled_base_qty', 40, 20)->default('0');
            $table->decimal('filled_quote_qty', 40, 20)->default('0');
            $table->decimal('price', 40, 20)->default('0');
            $table->string('token')->nullable()->unique();
            $table->json('symbol_info')->nullable();
            $table->longText("description")->nullable();
            $table->longText("user_description")->nullable();
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
        Schema::dropIfExists('symbol_orders');
    }
}
