<?php

namespace App\Listeners;

use App\Events\DepositChanged;
use App\Message;
use App\Models\Constants\MessageType;
use App\User;
use App\UserMessageStatus;
use Log;
use Mail;

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

        $subject = "Telah dilakukan ". $movementSign ." pada deposit akun anda.";

        Mail::queue("email.deposit", $viewData,
            function ($mail) use ($user, $movementSign, $subject) {

                $subject = "Telah dilakukan ". $movementSign ." pada deposit akun anda.";

                $mail->to($user->email, $user->name);
                $mail->subject($subject);
            }
        );
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
