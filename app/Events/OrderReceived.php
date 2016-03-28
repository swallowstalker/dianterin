<?php

namespace App\Events;

use App\Events\Event;
use App\Order;
use App\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderReceived extends Event
{
    use SerializesModels;

    public $order;
    public $author;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     * @param User $author
     */
    public function __construct(Order $order, User $author)
    {
        $this->order = $order;
        $this->author = $author;
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
