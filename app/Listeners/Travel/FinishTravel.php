<?php

namespace App\Listeners\Travel;

use App\CourierTravelRecord;
use App\Events\Travel\TravelIsFinishing;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class FinishTravel
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
     * @param  TravelIsFinishing  $event
     * @return void
     */
    public function handle(TravelIsFinishing $event)
    {
        $travel = $event->travel;
        $travel->status = CourierTravelRecord::STATUS_FINISHED;
        $travel->save();
    }
}
