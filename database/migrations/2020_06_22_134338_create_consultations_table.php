<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index();
            $table->bigInteger('doctor_id')->unsigned()->index();
            $table->bigInteger('schedule_id')->unsigned()->index();
            $table->string('consultation_number');
            $table->tinyInteger('type');
            $table->integer('consultation_fee');
            $table->datetime('date');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->text('additional_notes')->nullable();
            $table->tinyInteger('status')->default(0);

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
        Schema::dropIfExists('consultations');
    }
}
