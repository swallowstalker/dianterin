<?php

namespace App\Events;

use App\Events\Event;
use App\MessageOwnedByUser;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageIsCreated extends Event
{
    use SerializesModels;

    public $messageOwnedByUser;

    /**
     * Create a new event instance.
     * @param MessageOwnedByUser $messageOwnedByUser
     */
    public function __construct(MessageOwnedByUser $messageOwnedByUser)
    {
        $this->messageOwnedByUser = $messageOwnedByUser;
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
