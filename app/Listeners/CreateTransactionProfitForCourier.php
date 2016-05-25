<?php

namespace App\Listeners;

use App\Events\OrderReceived;
use App\Events\TravelProfitChanged;
use App\GeneralTransaction;
use App\TransactionOrder;
use App\TransactionProfit;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

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

        $generalTransaction = GeneralTransaction::whereHas("profit", function ($query) use ($travelID) {
            $query->where("travel_id", $travelID);
        })->first();

        if (empty($generalTransaction)) { // create

            $generalTransaction = GeneralTransaction::create([
                "author_id" => User::SYSTEM_USER,
                "user_id" => $event->travel->courier_id,
                "movement" => $totalProfitForCourier,
                "action" => "Travel #". $event->travel->id,
                "code" => "PROFIT"
            ]);

            TransactionProfit::create([
                "general_id" => $generalTransaction->id,
                "travel_id" => $event->travel->id
            ]);

        } else { // update

            $generalTransaction->movement = $totalProfitForCourier;
            $generalTransaction->save();

        }

    }
}
