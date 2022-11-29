<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultationCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_calls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('consultation_id')->unsigned()->index();
            $table->bigInteger('caller_id')->unsigned()->index();
            $table->string('caller_type');
            $table->bigInteger('receiver_id')->unsigned()->index();
            $table->string('receiver_type');
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();

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
        Schema::dropIfExists('consultation_calls');
    }
}
