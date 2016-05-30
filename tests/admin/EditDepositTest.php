<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutEvents;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class EditDepositTest extends TestCase
{
    use DatabaseTransactions, WithoutMiddleware, WithoutEvents;

    public function testCorrectEdit()
    {
        $this->actingAs(User::find(1));

        $this->post(
            route("admin.deposit.edit"),
            [
                "id" => 1,
                "adjustment" => 900,
                "reason" => "INI TEST",
                "password" => "123456"
            ]
        );

        $this->assertRedirectedToRoute("admin.user");
    }

    public function testIncorrectIDEdit()
    {
        $this->actingAs(User::find(1));

        $this->post(
            route("admin.deposit.edit"),
            [
                "id" => -99,
                "adjustment" => -1000,
                "reason" => "INI TEST",
                "password" => "123456"
            ]
        );

        $this->assertRedirectedTo("/");
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

        $this->assertRedirectedToRoute("admin.deposit");
    }
}
