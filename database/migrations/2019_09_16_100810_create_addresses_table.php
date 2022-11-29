<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->index();
            $table->bigInteger('region_id')->unsigned()->index();
            $table->bigInteger('province_id')->unsigned()->index();
            $table->bigInteger('city_id')->unsigned()->index();
            $table->string('unit')->nullable();
            $table->string('building')->nullable();
            $table->string('street');
            $table->string('zip');
            $table->string('landmark')->nullable();
            $table->boolean('default')->default(0);
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
        Schema::dropIfExists('addresses');
    }
}
