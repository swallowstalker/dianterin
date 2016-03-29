<?php

namespace App\Http\Controllers\User;

use App\Message;
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
    public function dismiss(Request $request) {

        $this->validate($request, [
            "id" => "required|numeric|exists:notification,id"
        ]);

        $message = Message::where("id", $request->input("id"))->first();
        $message->status = true;
        $message->save();

        return new JsonResponse();
    }

}
