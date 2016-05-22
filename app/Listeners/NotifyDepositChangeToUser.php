<?php

namespace App\Listeners;

use App\Events\DepositChanged;
use App\Events\OrderLocked;
use App\Order;
use App\PendingTransactionOrder;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
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

        Mail::send("email.deposit", $viewData,
            function ($mail) use ($user, $movementSign) {

                $subject = "Telah dilakukan ". $movementSign ." pada deposit akun anda.";

                $mail->from("strato@dianter.in", 'Dianterin');
                $mail->to($user->email, $user->name);
                $mail->subject($subject);
            }
        );
    }
}
