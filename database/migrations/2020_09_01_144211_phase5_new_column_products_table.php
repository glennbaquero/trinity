<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Phase5NewColumnProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('generic_name')->nullable();
            $table->integer('available_in')->nullable();
            $table->boolean('best_seller')->default(false);
            $table->boolean('new_arrival')->default(false);
            $table->boolean('promo')->default(false);
            $table->decimal('promo_price', 9, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
