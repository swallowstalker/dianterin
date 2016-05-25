<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTravelInTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_order', function (Blueprint $table) {
            $table->dropForeign("trans_to_travel");
        });

        Schema::table('transaction_order_pending', function (Blueprint $table) {
            $table->dropForeign("pending_trans_to_travel");
        });

        Schema::table('transaction_order', function (Blueprint $table) {
            $table->dropColumn("travel_id");
        });

        Schema::table('transaction_order_pending', function (Blueprint $table) {
            $table->dropColumn("travel_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_order', function (Blueprint $table) {
            $table->integer("travel_id")->nullable();

            $table->foreign('travel_id', 'trans_to_travel')
                ->references('id')->on('courier_travel')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('transaction_order_pending', function (Blueprint $table) {
            $table->integer("travel_id")->nullable();

            $table->foreign('travel_id', 'pending_trans_to_travel')
                ->references('id')->on('courier_travel')
                ->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }
}
