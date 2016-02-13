<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLaravelColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_customer', function (Blueprint $table) {
            $table->renameColumn("register_time", "created_at");
            $table->timestamp("updated_at");
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_customer', function (Blueprint $table) {
            $table->renameColumn("created_at", "register_time");
            $table->dropColumn("updated_at");
            $table->dropRememberToken();
        });
    }
}
