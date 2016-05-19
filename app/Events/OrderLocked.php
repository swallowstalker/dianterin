<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderLocked extends Event
{
    use SerializesModels;

    public $orderElementList = [];

    /**
     * Create a new event instance.
     *
     * @param array $orderElementList
     */
    public function __construct(array $orderElementList = [])
    {
        $this->orderElementList = $orderElementList;
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
