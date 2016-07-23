<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailSpoolerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("email_queue", function (Blueprint $table) {
            $table->integer("id", true);
            $table->text("destination_name");
            $table->text("destination_email");
            $table->text("subject");
            $table->text("content");
            $table->boolean("sent");
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
        Schema::drop("email_queue");
    }
}
