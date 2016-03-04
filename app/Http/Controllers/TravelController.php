<?php

namespace App\Http\Controllers;

use App\CourierTravelRecord;
use App\Http\Requests;
use App\Restaurant;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;

class TravelController extends Controller
{

    private $courierTravel;

    /**
     * Create a new controller instance.
     * @param CourierTravelRecord $courierTravel
     */
    public function __construct(CourierTravelRecord $courierTravel)
    {
        $this->middleware('auth');
        $this->courierTravel = $courierTravel;
    }

    /**
     * Retrieved on food choice popup showed up.
     * User then choice given courier.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getActiveCourierByRestaurant(Request $request) {

        $activeTravel = $this->courierTravel
            ->whereHas("visitedRestaurants", function($query) use ($request) {
                $query->where("allowed_restaurant", $request->input("restaurant_id"));
            })
            ->where("limit_time", ">", DB::raw("NOW()"))
            ->get();

        $responseList = [];
        foreach ($activeTravel as $travel) {

            $visitedRestaurant = $travel->visitedRestaurants()
                ->where("allowed_restaurant", $request->input("restaurant_id"))
                ->first();

            $responseList[] = (object) [
                "travel_id" => $travel->id,
                "courier_name" => $travel->courier->name,
                "cost" => $visitedRestaurant->delivery_cost
            ];
        }

        return new JsonResponse($responseList);
    }

    public function showList() {

        $viewData = [];
        $viewData["restaurantList"] = Restaurant::active()->get();

        return view('public.restaurant.list_restaurant', $viewData);
    }
}
