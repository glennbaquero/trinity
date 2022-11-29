<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuccessReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('success_referrals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('referrer_id')->unsigned()->index();
            $table->integer('referee_id')->unsigned()->index();
            $table->integer('invoice_id')->unsigned()->index();
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
        Schema::dropIfExists('success_referrals');
    }
}
