<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\GeneralTransaction;
use App\Order;
use App\TransactionOrder;
use App\User;
use Auth;
use Datatables;
use DB;
use Form;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Log;

class TransactionController extends Controller
{
    public function __construct() {

        //@todo authorize for admin only
    }

    /**
     * Show order list
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function overall() {

        $viewData = [];
        return view("admin.transaction.overall", $viewData);
    }

    /**
     * Show DT list data
     *
     * @return mixed
     */
    public function overallData() {

        $overallTransaction = GeneralTransaction::get();
        $owner = '{!! App\GeneralTransaction::where("user_id", $user_id)->first()->owner->name !!}';
        $author = '{!! App\GeneralTransaction::where("author_id", $author_id)->first()->author->name !!}';

        return Datatables::of($overallTransaction)
            ->addColumn("owner", $owner)
            ->addColumn("author", $author)
            ->make(true);
    }

    /**
     * Show transaction order list.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function order() {

        $viewData = [];
        return view("admin.transaction.order", $viewData);

    }

    /**
     * Prepare DT data for order transaction, for current date.
     * @return mixed
     */
    public function orderData() {

        $orderTransaction = TransactionOrder::where(DB::raw("DATE(created_at)"), DB::raw("CURDATE()"))->get();
        $owner = '{!! App\GeneralTransaction::where("user_id", $user_id)->first()->owner->name !!}';
        $revertLink = Form::open(["url" => "admin/transaction/order/revert"]);
        $revertLink .= Form::hidden("id", '{!! $order_id !!}');
        $revertLink .= Form::submit("Revert", ["class" => "button-blue-white"]);
        $revertLink .= Form::close();

        return Datatables::of($orderTransaction)
            ->addColumn("owner", $owner)
            ->addColumn("revert_link", $revertLink)
            ->make(true);
    }

    /**
     * Revert order transaction.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function revert(Request $request) {

        $this->validate($request, [
            "id" => "required|exists:order_parent,id|exists:transaction_order,order_id"
        ]);

        // revert deposit debit
        $this->rollbackUserTransaction($request->input("id"));

        // delete transaction order
        TransactionOrder::where("order_id", $request->input("id"))->delete();

        // revert order status
        $revertedOrder = Order::find($request->input("id"));

        if ($revertedOrder->status == Order::STATUS_RECEIVED) {
            $revertedOrder->status = Order::STATUS_PROCESSED;
        } else if ($revertedOrder->status == Order::STATUS_RECEIVED_BY_FORCE) {
            $revertedOrder->status = Order::STATUS_NOT_RECEIVED;
        } else {
            Log::error("Reverted order status is neither received or received by force");
            Log::error("Current order status: ". $revertedOrder->status);
        }

        $revertedOrder->save();

        return redirect("admin/transaction/order");
    }

    /**
     * Rollback user balance.
     * @param $orderID
     */
    private function rollbackUserTransaction($orderID) {

        $transaction = TransactionOrder::where("order_id", $orderID)->first();

        // add new credit transaction for reverted order.
        $generalTransaction = new GeneralTransaction([
            "author_id" => Auth::user()->id,
            "user_id" => $transaction->user_id,
            "movement" => $transaction->final_cost,
            "action" => "REVERT: Payment for #". $orderID
        ]);

        $generalTransaction->save();
    }
}
