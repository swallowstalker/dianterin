<?php

namespace App\Listeners;

use App\Events\DepositChanged;
use App\Events\OrderLocked;
use App\Message;
use App\MessageOwnedByUser;
use App\Models\Constants\MessageType;
use App\Models\EmailQueue;
use App\Order;
use App\PendingTransactionOrder;
use App\User;
use App\UserMessageStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Log;
use Mail;
use View;

class NotifyDepositChangeToUser
{
    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param DepositChanged $event
     */
    public function handle(DepositChanged $event)
    {
        $user = User::find($event->transaction->user_id);

        $this->sendEmail($event, $user);
        $this->setNotificationBar($event);
    }

    private function sendEmail($event, $user) {

        $movement = $event->transaction->movement;
        $movementSign = "penambahan";

        if ($event->transaction->movement < 0) {
            $movementSign = "pengurangan";
            $movement = -1 * $movement;
        }

        $viewData = [
            "movement" => $movement,
            "movementSign" => $movementSign,
            "reason" => $event->transaction->action,
            "currentBalance" => $user->balance
        ];

        $depositChangeMail = view()->make("email.deposit", $viewData);
        $subject = "Telah dilakukan ". $movementSign ." pada deposit akun anda.";

        EmailQueue::create([
            "destination_name" => $user->name,
            "destination_email" => $user->email,
            "subject" => $subject,
            "content" => $depositChangeMail,
            "sent" => false
        ]);

//        Mail::send("email.deposit", $viewData,
//            function ($mail) use ($user, $movementSign) {
//
//                $subject = "Telah dilakukan ". $movementSign ." pada deposit akun anda.";
//
//                $mail->from("strato@dianter.in", 'Dianterin');
//                $mail->to($user->email, $user->name);
//                $mail->subject($subject);
//            }
//        );
    }

    private function setNotificationBar($event) {

        $movement = $event->transaction->movement;
        $movementSign = "penambahan";

        if ($event->transaction->movement < 0) {
            $movementSign = "pengurangan";
            $movement = -1 * $movement;
        }

        $message = new Message();
        $message->content = "Telah dilakukan ". $movementSign .
            " pada deposit akun anda, sebesar Rp ".
            number_format($movement, 0, ",", ".");
        $message->type = MessageType::NotificationBar;
        $message->save();

        $message->users()->create([
            "sender" => $event->transaction->author_id,
            "receiver" => $event->transaction->user_id,
            "status" => UserMessageStatus::Unread
        ]);

    }
}
