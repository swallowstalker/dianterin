<?php

namespace App\Http\Controllers\User;

use App\MessageOwnedByUser;
use App\Models\Constants\MessageType;
use App\Notification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageController extends Controller
{
    
    public function last(Request $request) {

        $lastMessage = MessageOwnedByUser::owner()->newest()
            ->type(MessageType::Popup)->first();

        if (empty($lastMessage)) {
            return new JsonResponse();
        } else {
            return new JsonResponse([
                "message" => $lastMessage->message->content,
                "id" => $lastMessage->id
            ]);
        }
    }

    public function dismiss(Request $request) {

        $this->validate($request, [
            "id" => "required|numeric|exists:message_user,id"
        ]);

        $messageOwnedByUser = MessageOwnedByUser::where("id", $request->input("id"))->first();
        $messageOwnedByUser->status = true;
        $messageOwnedByUser->save();

        return new JsonResponse();
    }
}
