<?php

namespace App\Events;

use App\Events\Event;
use App\OrderElement;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderDelivered extends Event
{
    use SerializesModels;

    public $orderElement;
    public $adjustment;
    public $adjustmentInfo;

    /**
     * Create a new event instance.
     *
     * @param OrderElement $orderElement
     * @param int $adjustment
     * @param string $adjustmentInfo
     */
    public function __construct(OrderElement $orderElement, $adjustment = 0, $adjustmentInfo = "")
    {
        $this->orderElement = $orderElement;
        $this->adjustment = $adjustment;
        $this->adjustmentInfo = $adjustmentInfo;
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
