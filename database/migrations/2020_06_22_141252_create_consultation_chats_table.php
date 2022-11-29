<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultationChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation_chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('consultation_id')->unsigned()->index();
            $table->bigInteger('sender_id')->unsigned()->index();
            $table->string('sender_type');
            $table->bigInteger('receiver_id')->unsigned()->index();
            $table->string('receiver_type');
            $table->text('message')->nullable();
            $table->string('file_path')->nullable();
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
        Schema::dropIfExists('consultation_chats');
    }
}
