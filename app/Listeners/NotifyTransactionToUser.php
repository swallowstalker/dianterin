<?php

namespace App\Listeners;

use App\Events\OrderLocked;
use App\Order;
use App\PendingTransactionOrder;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Log;
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

    //@fixme if problem persists, we must check how Mail::queue perform
    private function sendInvoices($orderElementIDList = []) {

        $notFoundOrderIDList = $this->findNotFoundOrder($orderElementIDList);

        // not found order list, grouped by user.
        $notFoundOrderListByUser = Order::whereIn("id", $notFoundOrderIDList)
            ->get()->groupBy("user_id");

        // transaction list of user who receive his/her order.
        $transactionByUser = PendingTransactionOrder::whereIn("order_id", array_keys($orderElementIDList))
            ->get()->groupBy("user_id");

        $userListWhoReceivedInvoices = array_unique(array_merge(
            array_keys($notFoundOrderListByUser->toArray()), array_keys($transactionByUser->toArray())
        ));

        // send invoice to user
        foreach ($userListWhoReceivedInvoices as $userID) {

            $user = User::find($userID);

            $transactionList = new Collection();
            $transactionListTotal = 0;
            if (isset($transactionByUser[$userID])) {
                $transactionList = $transactionByUser[$userID];
                $transactionListTotal = $transactionList->sum("final_cost");
            }

            $notFoundOrderListCurrentUser = new Collection();
            if (isset($notFoundOrderListByUser[$userID])) {
                $notFoundOrderListCurrentUser = $notFoundOrderListByUser[$userID];
            }

            $viewData = [
                "user" => $user,
                "transactionList" => $transactionList,
                "notFoundOrderList" => $notFoundOrderListCurrentUser,
                "total" => $transactionListTotal
            ];

            Mail::queue("email.billing_inline", $viewData, function ($mail) use (
                $user, $transactionList, $notFoundOrderListCurrentUser) {

                $subject = $user->name .", ".
                    $this->decideSubject($transactionList->toArray(), $notFoundOrderListCurrentUser->toArray());

                $mail->from("strato@dianter.in", 'Dianterin');
                $mail->to($user->email, $user->name);
                $mail->subject($subject);
            });
        }

    }

    /**
     * Decide invoice email subject.
     * @param array $transactionList
     * @param array $notFoundOrderListCurrentUser
     * @return string
     */
    private function decideSubject(array $transactionList, array $notFoundOrderListCurrentUser) {

        $subject = "";
        if (count($transactionList) != 0 && count($notFoundOrderListCurrentUser) != 0) {
            $subject = "pesanan anda telah tiba, namun ada pesanan yang tidak dapat diantarkan.";
        } else if (count($transactionList) != 0) {
            $subject = "pesanan anda telah tiba.";
        } else if (count($notFoundOrderListCurrentUser) != 0) {
            $subject = "mohon maaf, semua pesanan anda tidak dapat diantarkan.";
        }

        return $subject;
    }

    /**
     * Group not received order
     * @param array $orderElementIDList
     * @return array
     */
    private function findNotFoundOrder(array $orderElementIDList) {

        $notFoundOrderIDList = [];

        foreach ($orderElementIDList as $orderID => $orderElementID) {

            if ($orderElementID == 0) {
                $notFoundOrderIDList[] = $orderID;
            }
        }

        return $notFoundOrderIDList;
    }
}
