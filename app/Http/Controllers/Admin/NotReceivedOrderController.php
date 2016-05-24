<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\Events\OrderDelivered;
use App\Events\OrderLocked;
use App\Events\OrderReceived;
use App\Events\ProfitChanged;
use App\Events\TravelProfitChanged;
use App\Http\Requests\Admin\NotReceivedOrderLockRequest;
use App\Order;
use App\OrderElement;
use Auth;
use Event;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotReceivedOrderController extends Controller
{
    /**
     * Show order which is not received by user.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {

        $viewData = [];
        $viewData["openTravels"] = CourierTravelRecord::orderBy("id", "desc")
            ->get()->pluck("id", "id");

        $travel = $viewData["openTravels"]->first();
        if ($request->has("travel")) {
            $travel = $request->input("travel");
        }
        $viewData["travel"] = $travel;

        $viewData["notReceivedOrderList"] = Order::byStatus(Order::STATUS_NOT_RECEIVED)
            ->byTravel($travel)->get();

        return view("admin.order.not_received_order", $viewData);
    }

    /**
     * Change order status to "received by force"
     *
     * @param NotReceivedOrderLockRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function lock(NotReceivedOrderLockRequest $request) {

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
                
                Event::fire(new OrderLocked([$orderID => $chosenElementList]));

                Event::fire(new OrderReceived($orderElement->order, Auth::user()));
                Event::fire(new ProfitChanged(Auth::user()->id));
                Event::fire(new TravelProfitChanged($orderElement->order->travel));

                $order = $orderElement->order;
                $order->status = Order::STATUS_RECEIVED_BY_FORCE;
                $order->save();
            }
        }

        return new JsonResponse();
    }
}
