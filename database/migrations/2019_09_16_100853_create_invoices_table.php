<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('cart_id')->unsigned()->index();
            $table->integer('status_id')->unsigned()->index();
            $table->string('invoice_number');
            $table->string('code');
            $table->integer('payment_method');
            $table->boolean('payment_status')->default(0);
            $table->boolean('completed')->default(0);
            $table->integer('shipping_method');
            $table->string('shipping_name');
            $table->string('shipping_email');
            $table->string('shipping_mobile');
            $table->string('shipping_unit');
            $table->string('shipping_street');
            $table->string('shipping_region');
            $table->string('shipping_province');
            $table->string('shipping_city');
            $table->string('shipping_zip');
            $table->string('shipping_landmark')->nullable();
            $table->decimal('shipping_fee', 9, 2);
            $table->decimal('discount', 9, 2)->default(0);
            $table->decimal('sub_total', 9, 2)->default(0);
            $table->decimal('grand_total', 9, 2)->default(0);
            $table->string('deposit_slip_path')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
