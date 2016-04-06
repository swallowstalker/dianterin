<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\Events\OrderReceived;
use App\Events\ProfitChanged;
use App\GeneralTransaction;
use App\Order;
use App\User;
use Artisan;
use Auth;
use Datatables;
use DB;
use Event;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Log;

class CronController extends Controller
{

    /**
     * Show order list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function lockDeliveredOrder() {

        // auto debit unconfirmed order list if it has been delivered for over an hour
        $unconfirmedOrderList = Order::byStatus(Order::STATUS_DELIVERED)
            ->where(
                DB::raw("DATE_ADD(updated_at, INTERVAL 1 HOUR)"),
                "<=",
                DB::raw("NOW()")
            )->get();

        foreach ($unconfirmedOrderList as $order) {

            $order->status = Order::STATUS_RECEIVED;
            $order->save();

            Event::fire(new OrderReceived($order, User::find($order->user_id)));
        }

        Event::fire(new ProfitChanged(User::SYSTEM_USER));

    }

    /**
     * Make sure no order is left
     */
    public function finalizeOrphanedOrder() {


    }
}
