<?php

namespace App\Console\Commands;

use App\Events\OrderReceived;
use App\Events\ProfitChanged;
use App\Order;
use App\Profit;
use App\User;
use DB;
use Event;
use Illuminate\Console\Command;
use Log;
use Mail;

class ProfitNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profit:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify profit to admin';

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
        $this->sendProfitEmail();
    }

    public function sendProfitEmail() {

        $adminList = User::whereIn("id", [14, 30])->get();

        $profit = Profit::where("date", date("Y-m-d"))->first();
        $total = 0;
        if (! empty($profit)) {
            $total = $profit->total;
        }

        $this->info("Profit for ". date("Y-m-d") ." is: ". $total);

        $viewData = ["profit" => $total];

        foreach ($adminList as $admin) {

            Mail::send("email.profit", $viewData, function ($mail) use ($admin) {

                $mail->from("strato@dianter.in", 'Dianterin');
                $mail->to($admin->email, $admin->name);
                $mail->subject("Profit ". date("d") ."-". date("m") ."-". date("Y"));

            });
        }


    }
}