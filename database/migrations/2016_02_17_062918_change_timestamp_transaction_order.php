<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTimestampTransactionOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_order', function (Blueprint $table) {
            $table->renameColumn("transaction_time", "created_at");
        });

        Schema::table('transaction_order_pending', function (Blueprint $table) {
            $table->renameColumn("transaction_time", "created_at");
        });

        Schema::table('transaction_general', function (Blueprint $table) {
            $table->renameColumn("transaction_time", "created_at");
        });

        Schema::table('profit', function (Blueprint $table) {
            $table->renameColumn("timestamp", "created_at");
        });

        Schema::table('order_parent', function (Blueprint $table) {
            $table->renameColumn("time", "created_at");
            $table->timestamp("updated_at");
        });

        Schema::table('order_element', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::table('order_feedback', function (Blueprint $table) {
            $table->renameColumn("time", "created_at");
        });

        Schema::table('deposit_movement', function (Blueprint $table) {
            $table->renameColumn("create_time", "created_at");
        });

        Schema::table('courier_travel', function (Blueprint $table) {
            $table->renameColumn("time", "created_at");
            $table->timestamp("updated_at");
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
            $table->renameColumn("created_at", "transaction_time");
        });

        Schema::table('transaction_order_pending', function (Blueprint $table) {
            $table->renameColumn("created_at", "transaction_time");
        });

        Schema::table('transaction_general', function (Blueprint $table) {
            $table->renameColumn("created_at", "transaction_time");
        });

        Schema::table('profit', function (Blueprint $table) {
            $table->renameColumn("created_at", "timestamp");
        });

        Schema::table('order_parent', function (Blueprint $table) {
            $table->renameColumn("created_at", "time");
            $table->dropColumn("updated_at");
        });

        Schema::table('order_element', function (Blueprint $table) {
            $table->dropTimestamps();
        });

        Schema::table('order_feedback', function (Blueprint $table) {
            $table->renameColumn("created_at", "time");
        });

        Schema::table('deposit_movement', function (Blueprint $table) {
            $table->renameColumn("created_at", "create_time");
        });

        Schema::table('courier_travel', function (Blueprint $table) {
            $table->renameColumn("created_at", "time");
            $table->dropColumn("updated_at");
        });
    }
}
