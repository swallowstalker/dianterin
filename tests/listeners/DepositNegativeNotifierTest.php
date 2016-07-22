<?php

use App\Admin;
use App\GeneralTransaction;
use App\Models\Constants\MessageType;
use App\User;
use App\UserMessageStatus;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DepositNegativeNotifierTest extends TestCase
{
    use DatabaseTransactions, InteractsWithDatabase;

    protected function setUp()
    {
        parent::setUp();

        GeneralTransaction::where("user_id", 111)->delete();
        $result = GeneralTransaction::where("user_id", 111)->count();

        $this->assertTrue($result == 0);

        $generalTransaction = new GeneralTransaction();
        $generalTransaction->author_id = 1;
        $generalTransaction->user_id = 111;
        $generalTransaction->movement = -9900000;
        $generalTransaction->action = "TEST: test";
        $generalTransaction->code = "TEST";
        $generalTransaction->save();
    }

    public function testMessageForUser()
    {

        $this->seeInDatabase("messages", [
            "content" => "Saldo anda negatif, harap segera lakukan penambahan deposit.",
            "type" => MessageType::NotificationBar
        ]);

        $this->seeInDatabase("message_user", [
            "sender" => User::SYSTEM_USER,
            "receiver" => 111,
            "status" => UserMessageStatus::Unread
        ]);
    }


    public function testMessageForAdmin()
    {

        $suspect = User::find(111);

        $this->seeInDatabase("messages", [
            "content" => "Saldo ". $suspect->name .
                "(". $suspect->email .") negatif.",
            "type" => MessageType::NotificationBar
        ]);

        $adminList = Admin::all();

        foreach ($adminList as $admin) {

            $this->seeInDatabase("message_user", [
                "sender" => User::SYSTEM_USER,
                "receiver" => $admin->id,
                "status" => UserMessageStatus::Unread
            ]);
        }


    }
}
