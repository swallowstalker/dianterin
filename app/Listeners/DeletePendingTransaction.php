<?php

namespace App\Listeners;

use App\Events\Event;
use App\Events\OrderNotReceived;
use App\Events\OrderReceived;
use App\PendingTransactionOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeletePendingTransaction
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
     * @param Event $event
     */
    public function handle(Event $event)
    {
        if ($event instanceof OrderReceived || $event instanceof OrderNotReceived) {
            PendingTransactionOrder::where("order_id", $event->order->id)->delete();
        }
    }
}
