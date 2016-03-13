<?php

namespace App\Listeners;

use App\Events\OrderReceivedEvent;
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
     * @param  OrderReceivedEvent  $event
     * @return void
     */
    public function handle(OrderReceivedEvent $event)
    {

        $pendingTransaction = PendingTransactionOrder::where("order_id", $event->order->id)->first();

        // add new debit transaction for user buying this order
        $generalTransaction = new GeneralTransaction([
            "author_id" => Auth::user()->id,
            "user_id" => Auth::user()->id,
            "movement" => -1 * $pendingTransaction->final_cost,
            "action" => "ORDER: Payment for #". $event->order->id
        ]);

        $generalTransaction->save();
    }
}
