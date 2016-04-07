<?php

namespace App\Console\Commands;

use App\CourierTravelRecord;
use App\User;
use Illuminate\Console\Command;

class TravelCreator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'travel:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically create travel for today.';

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

        // from monday to thursday
        if (1 <= date("N") && date("N") <= 4) {
            CourierTravelRecord::create([
                "courier_id" => User::SYSTEM_DEFAULT_COURIER,
                "quota" => 0,
                "limit_time" => date("Y-m-d") ." 11:00:00"
            ]);
        } else if (date("N") == 5) { // special for friday
            CourierTravelRecord::create([
                "courier_id" => User::SYSTEM_DEFAULT_COURIER,
                "quota" => 0,
                "limit_time" => date("Y-m-d") ." 10:30:00"
            ]);
        }

        $this->info("Default travel created.");

    }
}
