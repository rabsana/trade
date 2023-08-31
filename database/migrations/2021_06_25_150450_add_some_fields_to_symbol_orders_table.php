<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeFieldsToSymbolOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('symbol_orders', function (Blueprint $table) {
            $table->decimal('original_base_qty', 40, 20)->default('0')->after('side');
            $table->decimal('original_quote_qty', 40, 20)->default('0')->after('base_qty');
            $table->decimal('commission', 40, 20)->default('0')->after('price');
            $table->decimal('commission_percent', 40, 20)->default('0')->after('commission');
            $table->json('commission_info')->nullable()->after('symbol_info');
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
            $table->dropColumn('original_base_qty');
            $table->dropColumn('original_quote_qty');
            $table->dropColumn('commission');
            $table->dropColumn('commission_percent');
            $table->dropColumn('commission_info');
        });
    }
}
