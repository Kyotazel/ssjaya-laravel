<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPriceInPharmacyProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pharmacy_products', function (Blueprint $table) {
            $table->bigInteger('price_stock')->default(0);
            $table->bigInteger('price_stock_sold')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pharmacy_products', function (Blueprint $table) {
            $table->dropColumn('price_stock');
            $table->dropColumn('price_stock_sold');
        });
    }
}
