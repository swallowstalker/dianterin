<?php

namespace App\Listeners;

use App\Events\OrderLocked;
use App\Order;
use App\PendingTransactionOrder;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class NotifyTransactionToUser
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderLocked  $event
     * @return void
     */
    public function handle(OrderLocked $event)
    {
        $this->sendInvoices($event->orderElementList);
    }

    private function sendInvoices($orderElementIDList = []) {

        $notFoundOrderIDList = [];

        //@todo we must group delivered and not received order.
        foreach ($orderElementIDList as $orderID => $orderElementID) {

            if ($orderElementID == 0) {
                $notFoundOrderIDList[] = $orderID;
            }
        }

        $notFoundOrderListByUser = Order::whereIn("id", $notFoundOrderIDList)
            ->get()->groupBy("user_id");

        //@fixme this is only send email to user who receive his/her order.
        $transactionByUser = PendingTransactionOrder::whereIn("order_id", array_keys($orderElementIDList))
            ->get()->groupBy("user_id");

        // send invoice per user
        foreach ($transactionByUser as $userID => $transactionList) {

            $user = User::find($userID);

            $notFoundOrderListCurrentUser = [];
            if (isset($notFoundOrderListByUser[$userID])) {
                $notFoundOrderListCurrentUser = $notFoundOrderListByUser[$userID];
            }

            $viewData = [
                "user" => $user,
                "transactionList" => $transactionList,
                "notFoundOrderList" => $notFoundOrderListCurrentUser,
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
