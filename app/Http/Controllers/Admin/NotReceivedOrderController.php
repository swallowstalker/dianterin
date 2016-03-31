<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\CourierVisitedRestaurant;
use App\Events\OrderDelivered;
use App\Events\OrderForceReceived;
use App\Events\OrderReceived;
use App\Order;
use App\OrderElement;
use App\PendingTransactionOrder;
use App\User;
use Auth;
use Datatables;
use DB;
use Event;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class NotReceivedOrderController extends Controller
{
    use OrderInvoices;

    /**
     * Show order which is not received by user.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request) {

        $viewData = [];
        $viewData["notReceivedOrderList"] = Order::byStatus(Order::STATUS_NOT_RECEIVED)->get();

        return view("admin.order.not_received_order", $viewData);
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

            // send forced order invoice via email
            $this->sendInvoices([$orderID]);

            Event::fire(new OrderReceived($orderElement->order, Auth::user()));

            $order = $orderElement->order;
            $order->status = Order::STATUS_RECEIVED_BY_FORCE;
            $order->save();
        }

        return redirect("admin/order/unreceived");
    }
}
