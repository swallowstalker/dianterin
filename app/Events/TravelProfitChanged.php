<?php

namespace App\Events;

use App\CourierTravelRecord;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TravelProfitChanged extends Event
{
    use SerializesModels;

    public $travel;

    /**
     * Create a new event instance.
     *
     * @param CourierTravelRecord $travel
     */
    public function __construct(CourierTravelRecord $travel)
    {
        $this->travel = $travel;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
