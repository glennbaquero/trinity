<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingVoucherIdInUsedVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('used_vouchers', function (Blueprint $table) {
            $table->integer('voucher_id')->unsigned()->index()->nullable();
            $table->integer('user_voucher_id')->nullable()->change();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('used_vouchers', function (Blueprint $table) {
            $table->dropColumn('voucher_id');
        });
    }
}
