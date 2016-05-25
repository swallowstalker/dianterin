<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCodeToTransGeneral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction_general', function (Blueprint $table) {
            $table->string("code", 10);
        });

        $allTransactionData = DB::table("transaction_general")->get();

        foreach ($allTransactionData as $transaction) {

            $actions = explode(":", $transaction->action);
            DB::table("transaction_general")
                ->where("id", $transaction->id)
                ->update(["code" => $actions[0]]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transaction_general', function (Blueprint $table) {
            $table->dropColumn("code");
        });
    }
}
