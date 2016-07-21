<?php

namespace App\Listeners;

use App\Admin;
use App\Events\DepositChanged;
use App\Message;
use App\Models\Constants\MessageType;
use App\User;
use App\UserMessageStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyDepositIfNegative
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  DepositChanged  $event
     * @return void
     */
    public function handle(DepositChanged $event)
    {
        $transactionOwner = $event->transaction->owner;
        if ($transactionOwner->balance < 0) {
            $this->notifyUserAboutNegativeDeposit($transactionOwner);
            $this->notifyAdminAboutNegativeDeposit($transactionOwner);
        }
    }

    private function notifyUserAboutNegativeDeposit($transactionOwner) {

        $message = new Message();
        $message->content = "Saldo anda negatif, ".
            "harap segera lakukan penambahan deposit.";
        $message->type = MessageType::NotificationBar;
        $message->save();

        $message->users()->create([
            "sender" => User::SYSTEM_USER,
            "receiver" => $transactionOwner->id,
            "status" => UserMessageStatus::Unread
        ]);

    }

    private function notifyAdminAboutNegativeDeposit($transactionOwner) {

        $message = new Message();
        $message->content = "Saldo ". $transactionOwner->name .
            "(". $transactionOwner->email .") negatif.";
        $message->type = MessageType::NotificationBar;
        $message->save();

        $adminList = Admin::all();

        foreach ($adminList as $admin) {
            $message->users()->create([
                "sender" => User::SYSTEM_USER,
                "receiver" => $admin->id,
                "status" => UserMessageStatus::Unread
            ]);
        }

    }
}
