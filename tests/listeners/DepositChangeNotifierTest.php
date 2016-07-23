<?php

use App\Admin;
use App\GeneralTransaction;
use App\Models\Constants\MessageType;
use App\User;
use App\UserMessageStatus;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DepositChangeNotifierTest extends TestCase
{
    use /*DatabaseTransactions, */InteractsWithDatabase;

    protected function setUp()
    {
        parent::setUp();

        GeneralTransaction::where("user_id", 111)->delete();
        $result = GeneralTransaction::where("user_id", 111)->count();

        $this->assertTrue($result == 0);

        $generalTransaction = new GeneralTransaction();
        $generalTransaction->author_id = 1;
        $generalTransaction->user_id = 111;
        $generalTransaction->movement = 99999;
        $generalTransaction->action = "TEST: test";
        $generalTransaction->code = "TEST";
        $generalTransaction->save();
    }

    public function testEmailQueue()
    {
        $user = User::find(111);

        $this->seeInDatabase("email_queue", [
            "destination_name" => $user->name,
            "destination_email" => $user->email,
            "subject" => "Telah dilakukan penambahan pada deposit akun anda.",
            "sent" => false
        ]);
    }

}
