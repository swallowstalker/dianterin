<?php

namespace App\Http\Controllers\Admin;

use App\Message;
use App\MessageOwnedByUser;
use App\Notification;
use App\User;
use App\UserMessageStatus;
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

        $message = new Message([
            "content" => $request->input("message"),
            "type" => $request->input("type")
        ]);
        $message->save();

        $userList = User::all();
        foreach ($userList as $user) {

            $message->users()->save(new MessageOwnedByUser([
                "sender" => Auth::user()->id,
                "receiver" => $user->id,
                "status" => UserMessageStatus::Unread
            ]));
        }

        return redirect("admin/message");
    }

}
