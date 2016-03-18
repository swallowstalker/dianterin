<?php

namespace App\Listeners;

use App\Events\OrderDelivered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class EmailUserOrderDelivered
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
     * @param OrderDelivered $event
     */
    public function handle(OrderDelivered $event)
    {
        //@todo send email to user that order is delivered.
        Log::debug("Email: your order is delivered!");
    }
}
