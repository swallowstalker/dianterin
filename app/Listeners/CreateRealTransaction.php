<?php

namespace App\Listeners;

use App\CourierVisitedRestaurant;
use App\Events\Event;
use App\Events\OrderForceReceived;
use App\Events\OrderReceived;
use App\GeneralTransaction;
use App\PendingTransactionOrder;
use App\TransactionOrder;
use Auth;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRealTransaction
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
     * @param OrderReceived $event
     */
    public function handle(OrderReceived $event)
    {

        $pendingTransaction = PendingTransactionOrder::where("order_id", $event->order->id)
            ->first();

        // create pending transaction
        $realTransaction = new TransactionOrder();

        $realTransaction->user_id = $pendingTransaction->user_id;
        $realTransaction->order_id = $pendingTransaction->order_id;

        $realTransaction->restaurant = $pendingTransaction->restaurant;
        $realTransaction->menu = $pendingTransaction->menu;

        $realTransaction->price = $pendingTransaction->price;
        $realTransaction->delivery_cost = $pendingTransaction->delivery_cost;

        $realTransaction->adjustment = $pendingTransaction->adjustment;
        $realTransaction->adjustment_info = $pendingTransaction->adjustment_info;

        $realTransaction->final_cost = $pendingTransaction->final_cost;

        $realTransaction->save();

    }
}
