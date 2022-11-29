<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoCallSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video_call_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->nullableMorphs('dispatchable');
            $table->nullableMorphs('receivable');
            $table->string('session')->nullable();
            $table->text('token')->nullable();
            $table->datetime('call_accepted_at')->nullable();
            $table->datetime('call_rejected_at')->nullable();
            $table->datetime('call_ended_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_call_sessions');
    }
}
