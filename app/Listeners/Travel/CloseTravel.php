<?php

namespace App\Listeners\Travel;

use App\CourierTravelRecord;
use App\Events\Travel\TravelIsClosing;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CloseTravel
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
     * @param  TravelIsClosing  $event
     * @return void
     */
    public function handle(TravelIsClosing $event)
    {
        $event->travel->limit_time = DB::raw("NOW()");
        $event->travel->status = CourierTravelRecord::STATUS_CLOSED;
        $event->travel->save();
    }
}
