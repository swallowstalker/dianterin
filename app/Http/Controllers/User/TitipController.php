<?php

namespace App\Http\Controllers\User;

use App\CourierTravelRecord;
use App\CourierVisitedRestaurant;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\TitipAddRestaurantRequest;
use App\Order;
use App\OrderElement;
use App\Restaurant;
use Auth;
use Session;

class TitipController extends Controller
{

    public function showStartPage() {

        $restaurantList = Restaurant::active()->get();
        $restaurantListDropdown = $restaurantList->pluck("name", "id");

        $viewData = [
            "restaurantList" => $restaurantListDropdown
        ];

        return view("public.titip.start", $viewData);
    }
    
    public function addRestaurant(TitipAddRestaurantRequest $request) {

        $restaurant = Restaurant::find($request->input("restaurant"));

        if (Session::has("titipRestaurantList")) {

            Session::push("titipRestaurantList", (object) [
                "data" => $restaurant,
                "cost" => $request->input("cost")
            ]);

        } else {

            Session::put("titipRestaurantList", [ (object) [
                "data" => $restaurant,
                "cost" => $request->input("cost")
            ]]);
        }

        return redirect()->route("user.titip.start");
    }

    public function open() {

        if (Session::has("titipRestaurantList")) {

            $travelRecord = CourierTravelRecord::create([
                "courier_id" => Auth::user()->id,
                "quota" => 0,
                "limit_time" => null
            ]);

            $restaurantList = Session::get("titipRestaurantList");

            foreach ($restaurantList as $restaurant) {

                $travelRecord->visitedRestaurants()->create([
                    "allowed_restaurant" => $restaurant->data->id,
                    "delivery_cost" => $restaurant->cost
                ]);

            }

            Session::forget("titipRestaurantList");

            Session::put("activeTravel", $travelRecord->id);

            return redirect()->route("user.titip.opened");

        } else {

            // @todo session flash for no restaurant

            return redirect()->route("user.titip.start");
        }
    }

    public function showOpened() {

        $activeTravelID = Session::get("activeTravel");
        $travel = CourierTravelRecord::where("id", $activeTravelID)->isOpen()->first();

        if (empty($travel)) {
            return redirect()->route("user.titip.start");
        }
        
        $orderElementList = OrderElement::with("order")
            ->whereHas("order", function($query) use ($activeTravelID) {
                $query->where("travel_id", $activeTravelID)
                    ->whereNotNull("travel_id");
            })
            ->get();

        $orderElementListByRestaurant = $orderElementList->groupBy("restaurant");
//        dd($orderElementList->groupBy("restaurant"));
//        dd($orderElementListByRestaurant);
//        dd($orderElementListByRestaurant[1]);
        
        

        return view("public.titip.opened", [
            "travel" => $travel,
            "orderElementListByRestaurant" => $orderElementListByRestaurant
        ]);
    }
}
