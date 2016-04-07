<?php

namespace App\Console\Commands;

use App\Events\OrderReceived;
use App\Events\ProfitChanged;
use App\Order;
use App\User;
use DB;
use Event;
use Illuminate\Console\Command;

class OrderSweeper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:sweep';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sweep old order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        // auto debit the delivered order which hasn't been confirmed "received" by user after 1 hour.
        $this->lockDeliveredOrder();

        // also sweep order in STATUS_ORDERED to become expired in 5 hours after travel limit time
        $this->changeOldOrderFlag();

        Event::fire(new ProfitChanged(User::SYSTEM_USER));
    }

    /**
     * Lock delivered order which exceeds limiting time.
     */
    public function lockDeliveredOrder() {

        // auto debit unconfirmed order list if it has been delivered for over an hour
        $unconfirmedOrderList = Order::byStatus(Order::STATUS_DELIVERED)
            ->where(
                DB::raw("DATE_ADD(updated_at, INTERVAL 1 HOUR)"),
                "<=",
                DB::raw("NOW()")
            )->get();

//        foreach ($unconfirmedOrderList as $order) {
//
//            $order->status = Order::STATUS_RECEIVED;
//            $order->save();
//
//            Event::fire(new OrderReceived($order, User::find($order->user_id)));
//        }

        $this->info("Order changed from delivered to received: ".
            count($unconfirmedOrderList) ." item(s)");

    }

    public function changeOldOrderFlag() {

        $orderTooOld = Order::whereHas('travel', function($query) {
                $query->where(
                    DB::raw("DATE_ADD(limit_time, INTERVAL 5 HOUR)"),
                    "<=",
                    DB::raw("NOW()")
                );
            });

        $orderedOrderList = $orderTooOld->byStatus(Order::STATUS_ORDERED)->get();
        $processedOrderList = $orderTooOld->byStatus(Order::STATUS_PROCESSED)->get();

        $notReceivedOrderList = Order::byStatus(Order::STATUS_NOT_RECEIVED)
            ->where(
                DB::raw("DATE_ADD(updated_at, INTERVAL 3 HOUR)"),
                "<=",
                DB::raw("NOW()")
            )->get();

//        foreach ($orderedOrderList as $order) {
//
//            $order->status = Order::STATUS_NOT_FOUND;
//            $order->save();
//        }
//
//        foreach ($processedOrderList as $order) {
//
//            $order->status = Order::STATUS_NOT_FOUND;
//            $order->save();
//        }

//        foreach ($notReceivedOrderList as $order) {
//
//            $order->status = Order::STATUS_NOT_FOUND;
//            $order->save();
//        }

        $this->info("Order changed from ordered to not found: ".
            count($orderedOrderList) ." item(s)");

        $this->info("Order changed from processed to not found: ".
            count($processedOrderList) ." item(s)");

        $this->info("Order changed from not received to not found: ".
            count($notReceivedOrderList) ." item(s)");

    }
}
