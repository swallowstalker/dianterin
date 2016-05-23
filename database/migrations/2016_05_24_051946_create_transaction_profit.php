<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionProfit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_profit', function (Blueprint $table) {
            $table->integer("id", true);
            $table->integer("general_id");
            $table->integer("travel_id");
        });

        Schema::table('transaction_profit', function (Blueprint $table) {

            $table->foreign("general_id", "link_to_trans_general")
                ->references('id')->on('transaction_general')
                ->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->foreign("travel_id", "link_to_travel")
                ->references('id')->on('courier_travel')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_profit', function (Blueprint $table) {

            $table->dropForeign("link_to_trans_general");
            $table->dropForeign("link_to_travel");
        });

        Schema::drop('transaction_profit');
    }
}
