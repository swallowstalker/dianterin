<?php

use App\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TransferDepositTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware, WithoutEvents, InteractsWithDatabase;

    public function testIncorrectTransfer()
    {
        $this->actingAs(User::find(1));

        $this->post(
            route("admin.transfer.action"),
            [
                "sender" => 1,
                "receiver" => 1,
                "amount" => 0,
                "reason" => "XXXXXXX",
                "password" => "123456"
            ]
        );

        $this->assertRedirectedTo("/");

        $this->dontSeeInDatabase("transaction_general", [
            "movement" => 0,
            "action" => "XXXXXXX",
        ]);
    }

    public function testCorrectTransfer()
    {
        $this->actingAs(User::find(1));

        $this->post(
            route("admin.transfer.action"),
            [
                "sender" => 1,
                "receiver" => 4,
                "amount" => 500,
                "reason" => "XXXXXXX",
                "password" => "123456"
            ]
        );

        $this->assertRedirectedToRoute("admin.user");

        $this->seeInDatabase("transaction_general", [
            "author_id" => 1,
            "user_id" => 1,
            "movement" => -500,
            "action" => "XXXXXXX",
            "code" => "TRANSFER"
        ]);

        $this->seeInDatabase("transaction_general", [
            "author_id" => 1,
            "user_id" => 4,
            "movement" => 500,
            "action" => "XXXXXXX",
            "code" => "TRANSFER"
        ]);
    }


}
