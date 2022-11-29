<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('medical_representative_id')->unsigned()->index();
            $table->integer('doctor_id')->unsigned()->index();
            $table->string('clinic');
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->string('agenda');
            $table->time('arrived_at')->nullable();
            $table->time('left_at')->nullable();
            $table->text('notes')->nullable();
            $table->string('signature')->nullable();
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
        Schema::dropIfExists('calls');
    }
}
