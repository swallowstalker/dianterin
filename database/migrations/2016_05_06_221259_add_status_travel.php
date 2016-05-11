<?php

use App\CourierTravelRecord;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusTravel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courier_travel', function (Blueprint $table) {
            $table->integer("status");
        });

        DB::table("courier_travel")->update(["status" => CourierTravelRecord::STATUS_FINISHED]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courier_travel', function (Blueprint $table) {
            $table->dropColumn("status");
        });
    }
}
