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
use Auth;
use Hash;
use Illuminate\Http\Request;

class DepositController extends Controller
{

    /**
     * Show edit deposit form
     * @todo coba mulai bikin unit test dari sini
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @internal param User $user
     */
    public function showEditDeposit(Request $request, $id) {

        $request->merge(["id" => $id]);

        $this->validate($request, [
            "id" => "required|numeric|exists:user_customer,id"
        ]);

        $userData = User::find($id);
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
            "id" => "required|numeric|exists:user_customer,id",
            "adjustment" => "required|numeric",
            "reason" => "required|max:500",
        ]);

        $adminPasswordCheck = Hash::check($request->input("password"), Auth::user()->password);

        if (! $adminPasswordCheck) {
            return back()->withErrors("Password not match");
        }

        $generalTransaction = new GeneralTransaction();
        $generalTransaction->author_id = Auth::user()->id;
        $generalTransaction->user_id = $request->input("id");
        $generalTransaction->movement = $request->input("adjustment");
        $generalTransaction->action = $request->input("reason");
        $generalTransaction->code = "DEPOSIT";
        $generalTransaction->save();

        return redirect()->route("admin.user");
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
            return back()->withErrors("Password not match");
        }

        $senderTransaction = new GeneralTransaction();
        $senderTransaction->author_id = Auth::user()->id;
        $senderTransaction->user_id = $request->input("sender");
        $senderTransaction->movement = -1 * $request->input("amount");
        $senderTransaction->action = $request->input("reason");
        $senderTransaction->code = "TRANSFER";
        $senderTransaction->save();

        $receiverTransaction = new GeneralTransaction();
        $receiverTransaction->author_id = Auth::user()->id;
        $receiverTransaction->user_id = $request->input("receiver");
        $receiverTransaction->movement = $request->input("amount");
        $receiverTransaction->action = $request->input("reason");
        $receiverTransaction->code = "TRANSFER";
        $receiverTransaction->save();

        return redirect()->route("admin.user");
    }
}