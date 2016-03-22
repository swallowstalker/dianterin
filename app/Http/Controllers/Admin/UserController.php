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
    public function __construct() {

        //@todo authorize for admin only
    }

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
        $edit = '<a href="'. url("/") .'/admin/deposit?id={!! $id !!}">Edit Deposit</a>';

        return Datatables::of($user)
            ->addColumn("balance", $balance)
            ->addColumn("edit_deposit", $edit)
            ->make(true);
    }

    /**
     * Show edit deposit form
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditDeposit(Request $request) {

        $this->validate($request, [
            "id" => "required|exists:user_customer,id"
        ]);

        $user = User::find($request->input("id"));
        $viewData = ["user" => $user];

        return view("admin.user.deposit", $viewData);
    }

    /**
     * Edit user deposit (for funds add/sub operation here)
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editDeposit(Request $request) {

        $this->validate($request, [
            "id" => "required|exists:user_customer,id",
            "adjustment" => "required|numeric",
            "reason" => "required|max:500",
        ]);

        $adminPasswordCheck = Hash::check($request->input("password"), Auth::user()->password);

        if (! $adminPasswordCheck) {
            return redirect("admin/deposit")->withErrors("Password not match");
        }

        $generalTransaction = new GeneralTransaction();
        $generalTransaction->author_id = Auth::user()->id;
        $generalTransaction->user_id = $request->input("id");
        $generalTransaction->movement = $request->input("adjustment");
        $generalTransaction->action = "DEPO: ". $request->input("reason");
        $generalTransaction->save();

        return redirect("admin/user");
    }

}
