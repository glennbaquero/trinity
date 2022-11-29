<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingMatricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_matrices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('area_id')->unsigned()->index();
            $table->boolean('free')->default(0);
            $table->decimal('fee', 9, 2);
            $table->boolean('quantity')->default(0);
            $table->integer('quantity_minimum')->nullable();
            $table->boolean('price')->default(0);
            $table->decimal('price_minimum', 9, 2)->nullable();
            $table->softDeletes();                        
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
        Schema::dropIfExists('shipping_matrices');
    }
}
