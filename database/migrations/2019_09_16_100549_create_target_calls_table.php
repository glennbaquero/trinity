<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('target_calls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('medical_representative_id')->unsigned()->index();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('target');
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
        Schema::dropIfExists('target_calls');
    }
}
