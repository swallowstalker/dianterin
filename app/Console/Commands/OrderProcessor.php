<?php

namespace App\Console\Commands;

use App\Order;
use App\Profit;
use App\User;
use DB;
use Event;
use Illuminate\Console\Command;
use Log;
use Mail;

class OrderProcessor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process ordered order which exceed travel limit time.';

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

        // update orders which exceed travel limit time
        $updatedOrderList = Order::byStatus(Order::STATUS_ORDERED)
            ->whereHas("travel", function ($query) {
                $query->where("limit_time", "<", DB::raw("NOW()"));
            })
            ->update(["status" => Order::STATUS_PROCESSED]);


        $this->info("Order changed from ordered to processed.");
    }

}
