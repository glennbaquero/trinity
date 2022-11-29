<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('specialization_id')->unsigned()->index();
            
            $table->string('brand_name');
            $table->string('name');
            $table->string('sku');
            $table->string('product_size');
            $table->string('image_path');

            $table->boolean('prescriptionable')->default(0);
            $table->boolean('is_other_brand')->default(0);

            $table->decimal('price', 9, 2);
            $table->decimal('client_points', 9, 2)->default(0);
            $table->decimal('doctor_points', 9, 2)->default(0);

            $table->text('ingredients')->nullable();
            $table->text('nutritional_facts')->nullable();
            $table->text('directions')->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('products');
    }
}
