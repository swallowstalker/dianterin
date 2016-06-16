<?php

namespace App\Http\Controllers\Admin;

use App\Notification;
use App\User;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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

            Notification::create($message);
        }

        return redirect("admin/message");
    }

}
