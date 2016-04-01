<?php

namespace App\Listeners;

use App\Events\ProfitChanged;
use App\Profit;
use App\TransactionOrder;
use Auth;
use DB;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProfit
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
     * @param  ProfitChanged  $event
     * @return void
     */
    public function handle(ProfitChanged $event)
    {
        $profit = Profit::firstOrNew(["date" => date("Y-m-d")]);

        // recollect today's all delivery costs
        $totalDeliveryCost = TransactionOrder::where(DB::raw("DATE(created_at)"), date("Y-m-d"))
            ->sum("delivery_cost");

        $profit->total = $totalDeliveryCost;
        $profit->user = Auth::user()->id;
        $profit->save();

    }
}
