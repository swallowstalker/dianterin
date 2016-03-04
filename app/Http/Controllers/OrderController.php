<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Menu;
use App\Order;
use App\OrderElement;
use App\Restaurant;
use Auth;
use Illuminate\Http\Request;
use Log;

class OrderController extends Controller
{

    private $order;
    private $menu;

    /**
     * Create a new controller instance.
     * @param Order $order
     * @param Menu $menu
     */
    public function __construct(Order $order, Menu $menu)
    {
        $this->middleware('auth');
        $this->order = $order;
        $this->menu = $menu;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $viewData = $this->getOrderSidebar();
        $viewData["restaurantList"] = Restaurant::active()->get();

//        Log::debug($viewData["orderedList"][0]->elements->toArray());

        return view('public.order.order', $viewData);
    }

    /**
     * Get order list / pending transaction for order sidebar.
     * @return array
     */
    private function getOrderSidebar() {

        $viewData = [];
        $viewData["orderedList"] = $this->order->byOwner()->byStatus(Order::STATUS_ORDERED)->get();
        Log::debug("ordered list");
        Log::debug($viewData["orderedList"]->count());
        $viewData["processedList"] = $this->order->byOwner()->byStatus(Order::STATUS_PROCESSED)->get();
        return $viewData;
    }

    public function add(Request $request) {


        if ($request->input("backup") != 1) {

            // saving new order parent
            $order = $this->order->create([
                "travel_id" => $request->input("travel"),
                "user_id" => Auth::user()->id,
                "status" => Order::STATUS_ORDERED
            ]);

            $request->session()->set("latest_order_id", $order->id);

        } else {

            $orderID = $request->session()->get("latest_order_id");
            $order = $this->order->find($orderID);

            if (empty($order)) {
                return redirect("/");
            }

            $request->session()->remove("latest_order_id");
        }




        // saving order element
        $menu = $this->menu->where("id", $request->input("menu"))->first();

        $element = new OrderElement([
            "restaurant" => $menu->restaurant_id,
            "menu" => $request->input("menu"),
            "preference" => $request->input("preference"),
            "amount" => 1,
        ]);

        $order->elements()->save($element);


        return redirect("/");
    }
}
