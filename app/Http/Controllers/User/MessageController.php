<?php

namespace App\Http\Controllers\User;

use App\MessageOwnedByUser;
use App\Notification;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class MessageController extends Controller
{
    /**
     * Mark as read for given notification
     * @param Request $request
     * @return JsonResponse
     */
    public function notificationDismiss(Request $request) {

        $this->validate($request, [
            "id" => "required|numeric|exists:notification,id"
        ]);

        $message = Notification::where("id", $request->input("id"))->first();
        $message->status = true;
        $message->save();

        return new JsonResponse();
    }

    public function last(Request $request) {

        $lastMessage = MessageOwnedByUser::owner()->newest()->first();

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
