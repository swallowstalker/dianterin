<?php

use App\Admin;
use App\GeneralTransaction;
use App\Profit;
use App\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProfitNotifierTest extends TestCase
{
    use DatabaseTransactions, InteractsWithDatabase;

    protected function setUp()
    {
        parent::setUp();

        Profit::where("date", date("Y-m-d"))->delete();

        $this->artisan("profit:notify");
    }

    public function testEmailQueue()
    {
        $adminList = Admin::all();

        foreach ($adminList as $admin) {
            $this->seeInDatabase("email_queue", [
                "destination_name" => $admin->name,
                "destination_email" => $admin->email,
                "subject" => "Profit ". date("d") ."-". date("m") ."-". date("Y"),
                "sent" => false
            ]);
        }
    }

}
