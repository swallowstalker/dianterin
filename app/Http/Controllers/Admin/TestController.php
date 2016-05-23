<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\CourierVisitedRestaurant;
use App\Events\OrderDelivered;
use App\Events\OrderLocked;
use App\Order;
use App\OrderElement;
use App\PendingTransactionOrder;
use App\TransactionOrder;
use App\User;
use Datatables;
use DB;
use Event;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use Mail;

class TestController extends Controller
{

    public function testInvoice() {

//        $chosenElementList = [4522 => 4878, 4521 => 0, 4520 => 0, 4519 => 0];
//        $chosenElementList = [4521 => 0];
        $chosenElementList = [4522 => 4878];
        Event::fire(new OrderLocked($chosenElementList));

    }

    public function seeBillingEmail() {
        return view("email.billing_raw");
    }

    //@fixme should not allow any nullable in travel ID (in order parent and in transactions)
    public function fixPendingAndActiveTransactionTravelID() {

        // pending transaction
        $transactionList = PendingTransactionOrder::all();

        foreach ($transactionList as $transaction) {

            $order = Order::find($transaction->order_id);
            $transaction->travel_id = $order->travel_id;
            $transaction->save();
        }

        // pending transaction
        $transactionList = TransactionOrder::all();

        foreach ($transactionList as $transaction) {

            $order = Order::find($transaction->order_id);
            $transaction->travel_id = $order->travel_id;
            $transaction->save();
        }

    }

}
