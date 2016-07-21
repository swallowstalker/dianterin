<?php

use App\User;
use Illuminate\Foundation\Testing\Concerns\InteractsWithDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class EditDepositTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware, WithoutEvents, InteractsWithDatabase;

    public function testCorrectEdit()
    {
        $this->actingAs(User::find(1));

        $this->post(
            route("admin.deposit.edit"),
            [
                "id" => 1,
                "adjustment" => 900,
                "reason" => "XXXXXXX",
                "password" => "123456"
            ]
        );

        $this->assertRedirectedToRoute("admin.user");

        $this->seeInDatabase("transaction_general", [
            "author_id" => 1,
            "user_id" => 1,
            "movement" => 900,
            "action" => "XXXXXXX",
            "code" => "DEPOSIT"
        ]);
    }

    public function testIncorrectIDEdit()
    {
        $this->actingAs(User::find(1));

        $this->post(
            route("admin.deposit.edit"),
            [
                "id" => -99,
                "adjustment" => -1000,
                "reason" => "XXXXXXX",
                "password" => "123456"
            ]
        );

        $this->assertRedirectedTo("/");

        $this->dontSeeInDatabase("transaction_general", [
            "action" => "XXXXXXX",
            "code" => "DEPOSIT"
        ]);
    }

    public function testEmptyField() {

        $this->actingAs(User::find(1));

        $this->post(
            route("admin.deposit.edit"),
            [
                "id" => "",
                "adjustment" => "",
                "reason" => "",
                "password" => ""
            ]
        );

        $this->assertRedirectedTo("/");
    }

    public function testIncorrectPassword() {

        $this->actingAs(User::find(1));

        $this->post(
            route("admin.deposit.edit"),
            [
                "id" => 1,
                "adjustment" => -1000,
                "reason" => "INI TEST",
                "password" => "1234567SALAH"
            ]
        );

        $this->assertRedirectedTo("/");
    }
}
