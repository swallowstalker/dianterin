<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Order;
use App\Restaurant;
use Illuminate\Http\Request;
use Log;

class OrderController extends Controller
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
        $viewData = [];
        $viewData["restaurantList"] = Restaurant::active()->get();

        $viewData["orderedList"] = Order::byOwner()->byStatus(Order::STATUS_ORDERED)->get();
        $viewData["processedList"] = Order::byOwner()->byStatus(Order::STATUS_PROCESSED)->get();

        Log::debug($viewData["orderedList"][0]->elements->toArray());

        return view('public.order.order', $viewData);
    }
}
