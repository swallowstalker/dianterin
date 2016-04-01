<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\GeneralTransaction;
use App\Order;
use App\User;
use Auth;
use Datatables;
use DB;
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

        //@todo auto debit unconfirmed order list if it has been delivered for over an hour
        $unconfirmedOrderList = Order::byStatus(Order::STATUS_DELIVERED)
            ->where(
                DB::raw("DATE_ADD(updated_at, INTERVAL 1 HOUR)"),
                "<=",
                DB::raw("NOW()")
            );

    }

    public function backupDatabase() {

        //@todo backup using laravel-backup. usahakan via email attachment aja sql-nya.
    }

}
