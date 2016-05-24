<?php

namespace App\Http\Controllers\User;

use App\CourierTravelRecord;
use App\CourierVisitedRestaurant;
use App\Events\OrderNotReceived;
use App\Events\OrderReceived;
use App\Events\ProfitChanged;
use App\Feedback;
use App\Http\Controllers\Controller;
use App\Http\Requests;
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

class OrderController extends Controller
{

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

        $restaurantWhoseCourierIsAvailable = CourierVisitedRestaurant::whereHas("travel",
            function ($query) {
                $query->byStatus(CourierTravelRecord::STATUS_OPENED);
            })
            ->get()
            ->pluck("allowed_restaurant")->toArray();

        $viewData["restaurantWhoseCourierIsAvailable"] = $restaurantWhoseCourierIsAvailable;

        $backupStatus = $request->session()->get("backup_status");

        if (empty($backupStatus)) {
            $backupStatus = 0;
        }

        $viewData["backupStatus"] = $backupStatus;

        $viewData["notifications"] = Message::active()
            ->byOwner()->byNewest()->get();

        return view('public.order.order', $viewData);
    }

    /**
     * Get order list / pending transaction for order sidebar.
     * @return array
     */
    private function getOrderSidebar() {

        $viewData = [];

        $viewData["orderedList"] = Order::byOwner()
            ->byStatus(Order::STATUS_ORDERED)->today()->get();
        $viewData["processedList"] = Order::byOwner()
            ->byStatus(Order::STATUS_PROCESSED)->today()->get();

        $viewData["pendingTransactionList"] = PendingTransactionOrder::byOwner()
            ->today()->get();

        return $viewData;
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

    /**
     * Mark order as received.
     * Move pending transaction.
     * Save feedback.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function received(Request $request) {

        $this->validate($request, [
            "id" => "required|exists:order_parent,id|exists:transaction_order_pending,order_id",
            "feedback" => "max:1000"
        ]);

        $order = Order::where("id", $request->input("id"))
            ->where("status", Order::STATUS_DELIVERED)
            ->first();

        $this->authorize($order);

        $order->status = Order::STATUS_RECEIVED;
        $order->save();

        Event::fire(new OrderReceived($order, Auth::user()));
        Event::fire(new ProfitChanged(Auth::user()->id));

        $this->saveFeedback($order->id, $request->input("feedback"));

        return redirect("/");
    }

    /**
     * Save feedback sent from user.
     *
     * @param $orderID
     * @param $feedbackMessage
     */
    private function saveFeedback($orderID, $feedbackMessage) {

        // save in feedback table
        if (! empty($feedbackMessage)) {

            $feedback = new Feedback([
                "order_id" => $orderID,
                "feedback" => $feedbackMessage
            ]);

            $feedback->save();
        }
    }

    /**
     * Mark order as "not received"
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function notReceived(Request $request) {

        $this->validate($request, [
            "id" => "required|exists:order_parent,id",
        ]);

        $order = Order::where("id", $request->input("id"))
            ->where("status", Order::STATUS_DELIVERED)
            ->first();

        $this->authorize($order);

        $order->status = Order::STATUS_NOT_RECEIVED;
        $order->save();

        Event::fire(new OrderNotReceived($order));

        return redirect("/");
    }
}
