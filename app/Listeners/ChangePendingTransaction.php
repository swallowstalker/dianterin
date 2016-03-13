<?php

namespace App\Listeners;

use App\Events\OrderReceivedEvent;
use App\GeneralTransaction;
use App\PendingTransactionOrder;
use App\TransactionOrder;
use Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChangePendingTransaction
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
        // move pending transaction to transaction list
        $pendingTransaction = PendingTransactionOrder::where("order_id", $event->order->id)->first();

        $transaction = new TransactionOrder([
            "user_id" => $pendingTransaction->user_id,
            "order_id" => $pendingTransaction->order_id,
            "restaurant" => $pendingTransaction->restaurant,
            "menu" => $pendingTransaction->menu,
            "price" => $pendingTransaction->price,
            "delivery_cost" => $pendingTransaction->delivery_cost,
            "adjustment" => $pendingTransaction->adjustment,
            "adjustment_info" => $pendingTransaction->adjustment_info,
            "final_cost" => $pendingTransaction->final_cost,
        ]);

        $transaction->save();

        $pendingTransaction->delete();

    }
}
