<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalRepresentativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_representatives', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('region_id')->unsigned()->index()->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('fullname');
            $table->string('mobile');
            $table->string('image_path')->nullable();
            $table->timestamp('email_verified_at')->nullable();

            $table->rememberToken();

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
        Schema::dropIfExists('medical_representives');
    }
}
