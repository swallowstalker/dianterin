<?php
/**
 * Created by PhpStorm.
 * User: pulung
 * Date: 4/14/16
 * Time: 5:01 AM
 */

namespace App\Http\Controllers\Admin;


use App\GeneralTransaction;
use App\Http\Controllers\Controller;
use App\User;
use App\Validator\OrderValidator;
use Auth;
use Hash;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Http\Request;
use Validator;

class DepositController extends Controller
{

    /**
     * Show edit deposit form
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showEditDeposit(Request $request, User $user) {

        $this->validate($request, [
            "id" => "required|exists:user_customer,id"
        ]);

        $userData = $user->find($request->input("id"));
        $viewData = ["user" => $userData];

        return view("admin.deposit.edit", $viewData);
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

    /**
     * Show transfer form
     * @param User $user
     * @return mixed
     */
    public function showTransfer(User $user) {

        $userList = $user->orderBy("name")->get()->pluck("name", "id");
        $viewData = [
            "userList" => $userList,
            "defaultUser" => Auth::user()->id
        ];

        return view("admin.deposit.transfer", $viewData);
    }

    /**
     * Transfer action
     * @param Request $request
     * @return mixed
     */
    public function transfer(Request $request) {

        $this->validate($request, [
            "sender" => "required|exists:user_customer,id",
            "receiver" => "required|exists:user_customer,id|different:sender",
            "amount" => "required|numeric|min:1|sufficient_balance_for_transfer",
            "reason" => "required|max:500",
        ]);

        $adminPasswordCheck = Hash::check($request->input("password"), Auth::user()->password);

        if (! $adminPasswordCheck) {
            return redirect("admin/transfer")->withErrors("Password not match");
        }

        $generalTransaction = new GeneralTransaction();
        $generalTransaction->author_id = Auth::user()->id;
        $generalTransaction->user_id = $request->input("sender");
        $generalTransaction->movement = -1 * $request->input("amount");
        $generalTransaction->action = "TRANSFER: ". $request->input("reason");
        $generalTransaction->save();

        $generalTransaction = new GeneralTransaction();
        $generalTransaction->author_id = Auth::user()->id;
        $generalTransaction->user_id = $request->input("receiver");
        $generalTransaction->movement = $request->input("amount");
        $generalTransaction->action = "TRANSFER: ". $request->input("reason");
        $generalTransaction->save();

        return redirect("admin/user");
    }
}