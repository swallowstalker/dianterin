<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddActiveColumnToMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("dimenuin", function (Blueprint $table) {
            $table->boolean("status")->default(true);
        });

        Schema::table("direstoranin", function (Blueprint $table) {
            $table->boolean("status")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("dimenuin", function (Blueprint $table) {
            $table->dropColumn("status");
        });

        Schema::table("direstoranin", function (Blueprint $table) {
            $table->integer("status")->change();
        });
    }
}
