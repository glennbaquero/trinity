<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestClaimReferralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_claim_referrals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_by')->unsigned()->index();
            $table->integer('success_referral_id')->unsigned()->index();
            $table->dateTime('claimed_at')->nullable();
            $table->integer('distribute_by')->nullable();
            $table->dateTime('disapproved_at')->nullable();
            $table->integer('disapproved_by')->nullable();
            $table->text('reason')->nullable();
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
        Schema::dropIfExists('request_claim_referrals');
    }
}