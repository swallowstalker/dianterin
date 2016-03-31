<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\GeneralTransaction;
use App\Message;
use App\Order;
use App\TransactionOrder;
use App\User;
use Auth;
use Datatables;
use DB;
use Form;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Log;

class MessageController extends Controller
{

    /**
     * Show broadcast form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        return view("admin.message.broadcast");
    }

    /**
     * Broadcast message to all user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function broadcast(Request $request) {

        $this->validate($request, [
            "message" => "required|max:1000"
        ]);

        $userList = User::all();
        $messageList = [];

        foreach ($userList as $user) {

            $message = [
                "sender" => Auth::user()->id,
                "receiver" => $user->id,
                "status" => 0,
                "message" => $request->input("message")
            ];

            Message::create($message);
        }

        return redirect("admin/message");
    }

}
