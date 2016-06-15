<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageAndUserRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('message_user', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('message_id');
            $table->integer('sender');
            $table->integer('receiver');
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::table('message_user', function (Blueprint $table) {

            $table->foreign("sender", "message_sender")
                ->references('id')->on('user_customer')
                ->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->foreign("receiver", "message_receiver")
                ->references('id')->on('user_customer')
                ->onUpdate('CASCADE')->onDelete('CASCADE');

            $table->foreign("message_id", "pivot_to_message")
                ->references('id')->on('messages')
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
        Schema::table('message_user', function (Blueprint $table) {
            $table->dropForeign("message_sender");
            $table->dropForeign("message_receiver");
            $table->dropForeign("pivot_to_message");
        });

        Schema::drop('message_user');
        Schema::drop('messages');
    }
}
