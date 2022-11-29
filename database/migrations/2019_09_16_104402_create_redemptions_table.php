<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedemptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redemptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('merchant_id');
            $table->integer('denomination_id');
            $table->string('reference_no');
            $table->string('value');
            $table->string('price');
            $table->string('denomination_name');
            $table->string('merchant_name');
            $table->dateTime('expiry_date_time')->nullable();
            $table->text('urls'); // json
            $table->decimal('credits_used', 9, 2);
            $table->string('image_path');
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
        Schema::dropIfExists('redemptions');
    }
}
