<?php

namespace App\Listeners;

use App\CourierVisitedRestaurant;
use App\Events\OrderDelivered;
use App\PendingTransactionOrder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreatePendingTransaction
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

        $order = $event->orderElement->order;
        $orderElement = $event->orderElement;
        $visitedRestaurant = CourierVisitedRestaurant::where("travel_id", $order->travel_id)
            ->where("allowed_restaurant", $orderElement->restaurant)
            ->first();

        // create pending transaction
        $pendingTransaction = new PendingTransactionOrder();

        $pendingTransaction->user_id = $order->user_id;
        $pendingTransaction->order_id = $order->id;

        $pendingTransaction->restaurant = $orderElement->restaurantObject->name;
        $pendingTransaction->menu = $orderElement->menuObject->name;

        $pendingTransaction->price = $orderElement->subtotal;
        $pendingTransaction->delivery_cost = $visitedRestaurant->delivery_cost;

        $pendingTransaction->adjustment = $event->adjustment;
        $pendingTransaction->adjustment_info = $event->adjustmentInfo;

        $pendingTransaction->final_cost = $pendingTransaction->price
            + $pendingTransaction->delivery_cost + $pendingTransaction->adjustment;

        $pendingTransaction->save();
    }
}
