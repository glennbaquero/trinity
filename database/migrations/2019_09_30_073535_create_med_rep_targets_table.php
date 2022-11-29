<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedRepTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('med_rep_targets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('medical_representative_id')->unsigned()->index();
            $table->tinyInteger('type');
            $table->integer('month');
            $table->string('year');
            $table->decimal('value', 9, 2)->default(0);
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
        Schema::dropIfExists('med_rep_targets');
    }
}
