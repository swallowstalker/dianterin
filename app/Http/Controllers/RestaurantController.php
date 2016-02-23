<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Restaurant;
use Illuminate\Http\Request;
use Log;

class RestaurantController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('public.order.order');
    }

    public function showList() {

        $viewData = [];
        $viewData["restaurantList"] = Restaurant::active()->get();

        return view('public.restaurant.list_restaurant', $viewData);
    }
}
