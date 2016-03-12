<?php

namespace App\Policies;

use App\Order;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function cancel(User $user, Order $order) {

        if ($user->id == $order->user_id && $order->status == Order::STATUS_ORDERED) {
            return true;
        } else {
            return false;
        }
    }
}
