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

class UserController extends Controller
{

    /**
     * Show order list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        $viewData = [];
        $viewData["totalBalance"] = GeneralTransaction::sum("movement");
        $viewData["totalUser"] = User::count();

        return view("admin.user.user", $viewData);
    }

    /**
     * Show DT list data
     *
     * @return mixed
     */
    public function data() {

        $user = User::orderBy("name", "asc")->get();
        $balance = '{!! App\User::find($id)->balance !!}';
        $edit = '<a href="'. url("/") .'/admin/deposit/{!! $id !!}" class="button-blue-white">Edit Deposit</a>';

        return Datatables::of($user)
            ->addColumn("balance", $balance)
            ->addColumn("edit_deposit", $edit)
            ->make(true);
    }

}
