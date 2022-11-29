<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveStartAndEndDateToTargetCallsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('target_calls', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
            $table->integer('month');
            $table->string('year');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('target_calls', function (Blueprint $table) {
            $table->dropColumn(['month', 'year']);
            $table->date('start_date');
            $table->date('end_date');
        });
    }
}
