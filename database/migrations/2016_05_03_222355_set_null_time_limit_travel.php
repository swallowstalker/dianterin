<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetNullTimeLimitTravel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courier_travel', function (Blueprint $table) {
            $table->dateTime("limit_time")->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courier_travel', function (Blueprint $table) {
            $table->dateTime("limit_time")->change();
        });
    }
}
