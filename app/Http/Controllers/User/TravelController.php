<?php

namespace App\Http\Controllers\User;

use App\CourierTravelRecord;
use App\Http\Controllers\Controller;
use App\Restaurant;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Log;

class TravelController extends Controller
{

    /**
     * Retrieved on food choice popup showed up.
     * User then choice given courier.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getActiveCourierByRestaurant(Request $request) {

        $restaurantID = $request->input("restaurant_id");

        $activeTravel = CourierTravelRecord::whereHas("visitedRestaurants", function($query) use ($restaurantID) {
                $query->where("allowed_restaurant", $restaurantID);
            })
            ->byStatus(CourierTravelRecord::STATUS_OPENED)->get();

        $responseList = [];
        foreach ($activeTravel as $travel) {

            $visitedRestaurant = $travel->visitedRestaurants()
                ->where("allowed_restaurant", $restaurantID)
                ->first();

            $responseList[] = (object) [
                "travel_id" => $travel->id,
                "courier_name" => $travel->courier->name,
                "cost" => $visitedRestaurant->delivery_cost
            ];
        }

        return new JsonResponse($responseList);
    }

    /**
     * Get list of active restaurant
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showList() {

        $viewData = [];
        $viewData["restaurantList"] = Restaurant::get();

        return view('public.restaurant.list_restaurant', $viewData);
    }
}
