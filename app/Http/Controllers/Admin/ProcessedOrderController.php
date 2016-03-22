<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\CourierVisitedRestaurant;
use App\Events\OrderDelivered;
use App\Order;
use App\OrderElement;
use App\PendingTransactionOrder;
use App\User;
use Datatables;
use DB;
use Event;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class ProcessedOrderController extends Controller
{

    /**
     * Show list of processed order, ready to mark "delivered"
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {

        $viewData = [];
        $viewData["openTravels"] = CourierTravelRecord::isOpen()->get()->pluck("id", "id");
        $viewData["processedOrderList"] = Order::byStatus(Order::STATUS_PROCESSED)
            ->byTravel($request->input("travel"))->get();

        return view("admin.order.delivered_order", $viewData);
    }

    /**
     * Change order status to "delivered"
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function lock(Request $request) {

        $adjustmentList = $request->input("adjustment");
        $infoAdjustmentList = $request->input("info_adjustment");
        $chosenElementList = $request->input("chosen_element");

        foreach ($chosenElementList as $orderID => $chosenElementID) {

            if ($chosenElementID == 0) {

                // change order status to not found

                $order = Order::find($orderID);
                $order->status = Order::STATUS_NOT_FOUND;
                $order->save();

                continue;
            }

            $orderElement = OrderElement::find($chosenElementID);

            Event::fire(new OrderDelivered(
                $orderElement,
                $adjustmentList[$orderID],
                $infoAdjustmentList[$orderID]
            ));

            $order = $orderElement->order;
            $order->status = Order::STATUS_DELIVERED;
            $order->save();
        }

        return redirect("admin/order/processed");
    }

    /**
     * Show processed order summary for courier
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showSummary(Request $request) {

        $processedOrderList = Order::byStatus(Order::STATUS_PROCESSED)
            ->byTravel($request->input("travel"))->get();

        // first, separate order element into priority list

        $orderElementByPriority = [];

        foreach ($processedOrderList as $order) {

            foreach ($order->elements as $key => $element) {

                if (! isset($orderElementByPriority[$key])) {
                    $orderElementByPriority[$key] = [];
                }

                $orderElementByPriority[$key][] = $element;
            }
        }

        // second, group it by restaurant.

        $orderElementByPriorityAndRestaurant = [];

        foreach ($orderElementByPriority as $key => $orderElementList) {

            $orderElementByPriorityAndRestaurant[$key] = [];

            foreach ($orderElementList as $orderElement) {

                if (! isset($orderElementByPriorityAndRestaurant[$key][$orderElement->restaurant])) {
                    $orderElementByPriorityAndRestaurant[$key][$orderElement->restaurant] = [];
                }

                $orderElementByPriorityAndRestaurant[$key][$orderElement->restaurant][] = $orderElement;

            }
        }

        $viewData = ["orderElementByPriorityAndRestaurant" => $orderElementByPriorityAndRestaurant];

        return view("admin.order.summary", $viewData);
    }
}
