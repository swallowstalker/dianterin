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

class TransactionController extends Controller
{
    public function __construct() {

        //@todo authorize for admin only
    }

    /**
     * Show order list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overall() {

        $viewData = [];
        return view("admin.transaction.overall", $viewData);
    }

    /**
     * Show DT list data
     *
     * @return mixed
     */
    public function overallData() {

        $overallTransaction = GeneralTransaction::get();
        $owner = '{!! App\GeneralTransaction::where("user_id", $user_id)->first()->owner->name !!}';
        $author = '{!! App\GeneralTransaction::where("author_id", $author_id)->first()->author->name !!}';

        return Datatables::of($overallTransaction)
            ->addColumn("owner", $owner)
            ->addColumn("author", $author)
            ->make(true);
    }

}
