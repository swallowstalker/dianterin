<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\Events\OrderDelivered;
use App\Events\OrderLocked;
use App\Events\Travel\TravelIsFinishing;
use App\Http\Requests\Admin\ProcessedOrderLockRequest;
use App\Order;
use App\OrderElement;
use PDF;
use Event;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        $viewData["openTravels"] = CourierTravelRecord::orderBy("id", "desc")
            ->get()->pluck("id", "id");

        $travelID = $viewData["openTravels"]->first();
        if ($request->has("travel")) {
            $travelID = $request->input("travel");
        }
        $viewData["travelID"] = $travelID;

        $processedOrderList = Order::byStatus(Order::STATUS_PROCESSED)
            ->byTravel($travelID)->get();

        if (count($processedOrderList) > 0) {

            $orderElementByPriorityAndRestaurant = $this->groupOrderElementByPriorityAndRestaurant($processedOrderList);
            $firstPriorityRestaurantIDList = array_keys($orderElementByPriorityAndRestaurant[0]);

            $processedOrderList = $processedOrderList->sortBy(function ($order, $key) use ($firstPriorityRestaurantIDList) {
                return array_search($order->elements->first()->restaurant, $firstPriorityRestaurantIDList);
            });
        }

        $viewData["processedOrderList"] = $processedOrderList;

        return view("admin.order.processed_order", $viewData);
    }

    /**
     * Change order status to "delivered"
     * @param ProcessedOrderLockRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function lock(ProcessedOrderLockRequest $request) {

        $adjustmentList = $request->input("adjustment");
        $infoAdjustmentList = $request->input("info_adjustment");
        $chosenElementList = $request->input("chosen_element");

        foreach ($chosenElementList as $orderID => $chosenElementID) {

            if ($chosenElementID == 0) {

                // change order status to not found
                $order = Order::find($orderID);
                $order->status = Order::STATUS_NOT_FOUND;
                $order->save();

            } else {

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
        }

        Event::fire(new OrderLocked($chosenElementList));

        $travel = CourierTravelRecord::find($request->input("travel"));
        Event::fire(new TravelIsFinishing($travel));

        return redirect("admin/order/processed");
    }

    /**
     * Show processed order summary for courier
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function showSummary(Request $request) {

        $viewData["openTravels"] = CourierTravelRecord::orderBy("id", "desc")
            ->get()->pluck("id", "id");
        $travel = $viewData["openTravels"]->first();

        if ($request->has("travel")) {
            $travel = $request->input("travel");
        }

        $processedOrderList = Order::byStatus(Order::STATUS_PROCESSED)
            ->byTravel($request->input("travel"))->get();

        $orderElementByPriorityAndRestaurant = $this->groupOrderElementByPriorityAndRestaurant($processedOrderList);

        $viewData = [
            "orderElementByPriorityAndRestaurant" => $orderElementByPriorityAndRestaurant,
            "travel" => $travel
        ];

        $pdf = PDF::loadView("admin.order.pdf.summary", $viewData);
        return $pdf->stream();
    }

    private function groupOrderElementByPriorityAndRestaurant($processedOrderList) {

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

        return $orderElementByPriorityAndRestaurant;
    }
}
