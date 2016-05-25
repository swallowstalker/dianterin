<?php

namespace App\Listeners;

use App\Events\OrderReceived;
use App\GeneralTransaction;
use App\PendingTransactionOrder;
use Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DebitUserBalance
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderReceived  $event
     * @return void
     */
    public function handle(OrderReceived $event)
    {

        $pendingTransaction = PendingTransactionOrder::where("order_id", $event->order->id)->first();

        // add new debit transaction for user buying this order
        $generalTransaction = new GeneralTransaction([
            "author_id" => $event->author->id,
            "user_id" => $event->order->user_id,
            "movement" => -1 * $pendingTransaction->final_cost,
            "action" => "Payment for #". $event->order->id,
            "code" => "ORDER"
        ]);

        $generalTransaction->save();
    }
}
