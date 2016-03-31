<?php
/**
 * Created by PhpStorm.
 * User: pulung
 * Date: 3/31/16
 * Time: 10:31 PM
 */

namespace App\Http\Controllers\Admin;


use App\PendingTransactionOrder;
use App\User;
use Mail;

trait OrderInvoices
{
    /**
     * @param $orderList
     */
    protected function sendInvoices($orderList) {

        $transactionByUser = PendingTransactionOrder::whereIn("order_id", $orderList)
            ->get()->groupBy("user_id");

        foreach ($transactionByUser as $userID => $transactionList) {

            $user = User::find($userID);

            $viewData = [
                "user" => $user,
                "transactionList" => $transactionList,
                "total" => $transactionList->sum("final_cost")
            ];

            Mail::send("email.billing_inline", $viewData, function ($mail) use ($user) {

                $mail->from("strato@dianter.in", 'Dianterin');
                $mail->to($user->email, $user->name);
                $mail->subject($user->name .", pesanan anda telah tiba.");

            });
        }

    }
}