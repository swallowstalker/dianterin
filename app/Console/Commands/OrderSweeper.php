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
        $this->lockDeliveredOrder();
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
//
//        Event::fire(new ProfitChanged(User::SYSTEM_USER));

        $this->info("Order changed from delivered to received: ".
            count($unconfirmedOrderList) ." item(s)");

    }
}
