<?php

namespace App\Http\Controllers\User;

use App\Events\OrderNotReceived;
use App\Events\OrderReceived;
use App\Events\ProfitChanged;
use App\Feedback;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\User\ChangeOrderElementAmountRequest;
use App\Http\Requests\User\NewOrderElementRequest;
use App\Menu;
use App\Message;
use App\Order;
use App\OrderElement;
use App\PendingTransactionOrder;
use App\Restaurant;
use App\TransactionOrder;
use Auth;
use Event;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Validator;

class OrderElementController extends Controller
{
    public function delete() {

        //@todo delete backup by order
        //@todo delete only backup whose parent order has "ordered" status

        //@todo if all element is deleted, delete the order too
    }

    /**
     * Add new order / backup order
     *
     * @param NewOrderElementRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function add(NewOrderElementRequest $request) {

        if ($request->input("backup") == 1) {

            $orderID = $request->session()->get("latest_order_id");
            $order = Order::find($orderID);

            if (empty($order)) {
                return redirect("/");
            }

            $request->session()->remove("latest_order_id");
            // if order element has reached total of 2, reset the backup input.
            $request->session()->flash("backup_status", 0);

        } else {

            // saving new order parent
            $order = Order::create([
                "travel_id" => $request->input("travel"),
                "user_id" => Auth::user()->id,
                "status" => Order::STATUS_ORDERED
            ]);

            $request->session()->set("latest_order_id", $order->id);
            $request->session()->flash("backup_status", 1);
        }

        // saving order element
        $menu = Menu::where("id", $request->input("menu"))->first();

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
     * Change order element amount
     *
     * @param ChangeOrderElementAmountRequest $request
     * @return JsonResponse
     */
    public function changeAmount(ChangeOrderElementAmountRequest $request) {

        $orderElement = OrderElement::where("id", $request->input("order_element_id"))->first();
        $orderElement->amount = $request->input("amount");
        $orderElement->save();

        $returnData = (object) [];
        $returnData->amount_response = $orderElement->amount;

        return new JsonResponse($returnData);
    }
}
