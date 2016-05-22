<?php

namespace App\Events;

use App\Events\Event;
use App\GeneralTransaction;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DepositChanged extends Event
{
    use SerializesModels;

    public $transaction;

    /**
     * Create a new event instance.
     *
     * @param GeneralTransaction $transaction
     */
    public function __construct(GeneralTransaction $transaction)
    {
        $this->transaction = $transaction;
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
