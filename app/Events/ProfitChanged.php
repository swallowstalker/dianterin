<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProfitChanged extends Event
{
    use SerializesModels;

    public $userID;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($userID)
    {
        $this->userID = $userID;
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
