<?php

namespace App\Listeners;

use App\Events\OrderReceived;
use App\Events\TravelProfitChanged;
use App\TransactionOrder;
use App\TransactionProfit;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateTransactionProfitForCourier
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
     * @param TravelProfitChanged $event
     */
    public function handle(TravelProfitChanged $event)
    {
        $travelID = $event->travel->id;

        $totalProfitForCourier = TransactionOrder::whereHas("order.travel", function ($query) use ($travelID) {

            $query->where("id", $travelID);

        })->sum("delivery_cost");

        $transactionProfit = TransactionProfit::firstOrNew([
            "travel_id" => $event->travel->id]);

        if ($transactionProfit->exists) {

            $generalTransaction = $transactionProfit->generalTransaction;
            $generalTransaction->movement = $totalProfitForCourier;
            $generalTransaction->save();

        } else {

            $transactionProfit->generalTransaction()->create([
                "author_id" => User::SYSTEM_USER,
                "user_id" => $event->travel->courier_id,
                "movement" => $totalProfitForCourier,
                "action" => "PROFIT: travel #". $event->travel->id
            ]);
        }
    }
}
