<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('doctor_id')->unsigned()->index();
            $table->integer('schedule_id')->unsigned()->index();
            $table->integer('consultation_id')->unsigned()->index();
            $table->string('reason');
            $table->bigInteger('approved_by')->unsigned()->index()->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->bigInteger('declined_by')->unsigned()->index()->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->string('disapproved_reason')->nullable();

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
        Schema::dropIfExists('refunds');
    }
}
