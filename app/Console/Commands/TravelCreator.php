<?php

namespace App\Console\Commands;

use App\CourierTravelRecord;
use App\CourierVisitedRestaurant;
use App\Restaurant;
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
        if (1 <= date("N") && date("N") <= 5) {

            $time = "11:00:00";

            if (date("N") == 5) {
                $time = "10:45:00";
            }

            $travel = CourierTravelRecord::create([
                "courier_id" => User::SYSTEM_DEFAULT_COURIER,
                "quota" => 0,
                "limit_time" => date("Y-m-d") ." ". $time,
                "status" => CourierTravelRecord::STATUS_OPENED
            ]);

            // add available restaurant for this newly created travel
            $this->registerActiveRestaurant($travel, $time);
        }

        $this->info("Default travel created.");

    }

    /**
     * Register active restaurant for daily operations.
     *
     * @param CourierTravelRecord $travel
     * @param $time
     */
    private function registerActiveRestaurant(CourierTravelRecord $travel, $time) {
        
        $visitedRestaurants = Restaurant::openAt($time)->get();
        foreach ($visitedRestaurants as $visitedRestaurant) {

            CourierVisitedRestaurant::create([
                "travel_id" => $travel->id,
                "allowed_restaurant" => $visitedRestaurant->id,
                "delivery_cost" => $visitedRestaurant->cost
            ]);
        }
        
    }

}
