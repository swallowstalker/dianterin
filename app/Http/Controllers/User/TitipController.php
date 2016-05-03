<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\TitipAddRestaurantRequest;
use App\Restaurant;
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


    }
}
