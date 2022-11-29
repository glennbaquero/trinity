<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /*  Schema::table('user_settings', function (Blueprint $table) {
            $table->boolean('share_records')->default(false);
        }); */

        Schema::table('blood_pressures', function (Blueprint $table) {
            $table->float('systole')->change();
            $table->float('diastole')->change();
        });

        Schema::table('blood_sugars', function (Blueprint $table) {
            $table->float('value')->change();
        });

        Schema::table('heart_rates', function (Blueprint $table) {
            $table->float('value')->change();
        });

        Schema::table('bmis', function (Blueprint $table) {
            $table->float('value')->change();
        });

        Schema::table('cholesterols', function (Blueprint $table) {
            $table->float('ldl')->change();
            $table->float('hdl')->change();
        });


        Schema::table('medical_representatives', function (Blueprint $table) {
            $table->string('mobile')->nullable()->change();
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
