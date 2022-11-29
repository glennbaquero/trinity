<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vouchers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('request_claim_referrals_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('voucher_id')->unsigned()->index();
            $table->string('code');
            $table->string('name');
            $table->integer('type');
            $table->decimal('discount', 9, 2)->default(0);
            $table->datetime('expired_at');
            $table->integer('max_usage')->default(1);
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
        Schema::dropIfExists('user_vouchers');
    }
}
