<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->foreignId('sales_id');
            $table->foreignId('pharmacy_id');
            $table->string('status')->default('BELUM LUNAS');
            $table->boolean('is_archived')->default(0);
            $table->string('white_purchase')->nullable();
            $table->string('yellow_purchase')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
