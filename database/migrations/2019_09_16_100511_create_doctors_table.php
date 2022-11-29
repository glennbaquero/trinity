<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('medical_representative_id')->unsigned()->index()->nullable();
            $table->integer('specialization_id')->unsigned()->index();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile_number');
            $table->string('qr_id')->nullable();
            $table->tinyInteger('class')->nullable();
            $table->string('clinic_address')->nullable();
            $table->string('clinic_hours')->nullable();
            // $table->decimal('consultation_fee', 9, 2);
            $table->string('brand_adaption_notes')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('qr_code_path')->nullable();
            $table->string('image_path')->nullable();
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
        Schema::dropIfExists('doctors');
    }
}
