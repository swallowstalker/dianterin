<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\OrderRequest;
use App\Menu;
use App\Order;
use App\OrderElement;
use App\Restaurant;
use Auth;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Validator;

class OrderController extends Controller
{

    private $order;
    private $menu;

    /**
     * Create a new controller instance.
     * @param Order $order
     * @param Menu $menu
     * @param Request $request
     */
    public function __construct(Order $order, Menu $menu, Request $request)
    {
        $this->middleware('auth');
        $this->order = $order;
        $this->menu = $menu;
    }

    /**
     * Show the application dashboard.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $viewData = $this->getOrderSidebar();
        $viewData["restaurantList"] = Restaurant::active()->orderBy("name", "asc")->get();

        //@todo shorten the backup status
//        $backupStatus = $request->session()->get("backup_status");
//
//        if (empty($backupStatus)) {
//            $backupStatus = 0;
//        }
//
//        $viewData["backupStatus"] = $backupStatus;

        return view('public.order.order', $viewData);
    }

    /**
     * Get order list / pending transaction for order sidebar.
     * @return array
     */
    private function getOrderSidebar() {

        $viewData = [];
        $viewData["orderedList"] = $this->order->byOwner()->byStatus(Order::STATUS_ORDERED)->get();
        $viewData["processedList"] = $this->order->byOwner()->byStatus(Order::STATUS_PROCESSED)->get();
        return $viewData;
    }

    /**
     * Add new order / backup order
     *
     * @param OrderRequest|Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(OrderRequest $request) {

        //@todo if order element has reached total of 2, reset the backup input.

        //@todo move this to "request"
        $validator = Validator::make($request->all(), [
            "backup" => "required|in:0,1",
            "menu" => "required|exists:dimenuin,id|sufficient_balance",
            "travel" => "required|exists:courier_travel,id",
            "preference" => "max:1000"
        ]);

        if ($validator->fails()) {

            if ($request->input("backup") == 1 || $request->input("backup") == 0) {
                $request->session()->flash("backup_status", $request->input("backup"));
            } else {
                $request->session()->flash("backup_status", 0);
            }

            $errorMessage = implode("<br/>", $validator->errors()->all());

            return redirect("/")
                ->with(["errorMessage" => $errorMessage, "errorFlag" => 1]);
        }


        if ($request->input("backup") == 1) {

            $orderID = $request->session()->get("latest_order_id");
            $order = $this->order->find($orderID);

            if (empty($order)) {
                return redirect("/");
            }

            $request->session()->remove("latest_order_id");
            $request->session()->flash("backup_status", 0);

        } else {

            // saving new order parent
            $order = $this->order->create([
                "travel_id" => $request->input("travel"),
                "user_id" => Auth::user()->id,
                "status" => Order::STATUS_ORDERED
            ]);

            $request->session()->set("latest_order_id", $order->id);
            $request->session()->flash("backup_status", 1);
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

    /**
     *
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changeAmount(Request $request) {

        $validator = Validator::make($request->all(), [
            "order_element_id" => "bail|required|exists:order_element,id|allow_change_amount",
            "amount" => "bail|required|numeric|min:1|max:7"
        ]);

        $orderElement = OrderElement::where("id", $request->input("order_element_id"))->first();

        $returnData = (object) [];

        if ($validator->fails()) {

            $returnData->amount_response = $orderElement->amount;

        } else {

            $orderElement->amount = $request->input("amount");
            $orderElement->save();

            $returnData->amount_response = $orderElement->amount;
        }

        return new JsonResponse($returnData);
    }


    /**
     * Cancel our own order with status "ordered"
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function cancel(Request $request) {

        $this->validate($request, [
            "id" => "required|exists:order_parent,id"
        ]);

        $order = Order::where("id", $request->input("id"))->first();

        $this->authorize($order);
        $order->delete();

        return redirect("/");
    }
}
