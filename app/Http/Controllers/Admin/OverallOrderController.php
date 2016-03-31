<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\Order;
use App\User;
use Datatables;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

class OverallOrderController extends Controller
{

    /**
     * Show order list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        $viewData = [];
        $viewData["openTravels"] = CourierTravelRecord::get()->pluck("id", "id");

        return view("admin.order.overall_order", $viewData);
    }

    /**
     * Show DT list data
     *
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request) {

        $order = Order::whereNotNull("travel_id")
            ->orderBy("created_at", "desc")->get();

        $element = '{!! App\Http\Controllers\Admin\OverallOrderController::unifyElements($id) !!}';
        $courierName = '{!! App\Http\Controllers\Admin\OverallOrderController::getCourierName($travel_id) !!}';
        $userName = '{!! App\Http\Controllers\Admin\OverallOrderController::getUserName($user_id) !!}';

        $deleteElement = '{!! Form::open(["url" => "admin/order/delete"]) !!}';
        $deleteElement .= '{!! Form::hidden("id", $id) !!}';
        $deleteElement .= '<button type="submit" style="border: 0; background: transparent;">'.
            '<i class="fa fa-trash"></i>'.
            '</button>';
        $deleteElement .= '{!! Form::close() !!}';

        return Datatables::of($order)
            ->editColumn("status", '{!! App\Order::$statusDescriptionList[$status] !!}')
            ->addColumn("name", $userName)
            ->addColumn("element", $element)
            ->addColumn("courier", $courierName)
            ->addColumn("delete", $deleteElement)
            ->make(true);
    }

    /**
     * Delete order whose status is "ordered"
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function delete(Request $request) {

        $this->validate($request, [
            "id" => "required|exists:order_parent,id"
        ]);

        Order::where("id", $request->input("id"))
            ->where("status", Order::STATUS_ORDERED)
            ->delete();

        return redirect("/admin/order");
    }

    /**
     * Change order status to "processed"
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function lock(Request $request) {

        $this->validate($request, [
            "travel" => "required|exists:courier_travel,id"
        ]);

        Order::where("status", Order::STATUS_ORDERED)
            ->where("travel_id", $request->input("travel"))
            ->update(["status" => Order::STATUS_PROCESSED]);

        return redirect("/admin/order");
    }

    /**
     * Unify order elements into one column
     *
     * @param $orderID
     * @return string
     */
    public static function unifyElements($orderID) {

        $order = Order::find($orderID);
        $orderDesc = "";

        foreach ($order->elements as $key => $element) {

            $description = "(". $element->amount ." buah) ". $element->restaurantObject->name .", ".
                $element->menuObject->name;

            if ($key != 0) {
                $description = '<span style="color: lightgrey;">'. $description .'</span>';
            }

            $orderDesc .= $description;
            $orderDesc .= '<br/>';
        }

        return $orderDesc;

    }

    /**
     * Get courier name for this order
     *
     * @param $travelID
     * @return mixed
     */
    public static function getCourierName($travelID) {

        $travel = CourierTravelRecord::find($travelID);
        return $travel->courier->name;
    }

    /**
     * Get user name for this order
     *
     * @param $userID
     * @return mixed
     */
    public static function getUserName($userID) {

        $user = User::find($userID);
        return $user->name;
    }


}
