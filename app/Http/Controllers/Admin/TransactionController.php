<?php

namespace App\Http\Controllers\Admin;

use App\CourierTravelRecord;
use App\Events\TravelProfitChanged;
use App\GeneralTransaction;
use App\Order;
use App\TransactionOrder;
use App\User;
use Auth;
use Datatables;
use DB;
use Event;
use Form;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Log;
use Psy\Util\Json;
use Symfony\Component\HttpFoundation\JsonResponse;

class TransactionController extends Controller
{

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
     * @param Request $request
     * @return mixed
     * @internal param string $dateFilter
     */
    public function overallData(Request $request) {

        $this->validate($request, ["dateFilter" => "date_format:Y-m-d"]);

        $dateFilter = $request->input("dateFilter");

        $overallTransaction = GeneralTransaction::select(["id", "user_id",
            "author_id", "movement", "code", "action", "created_at"]);

        if (! empty($dateFilter)) {
            $overallTransaction = $overallTransaction->where(
                DB::raw("DATE(created_at)"), "<=", $dateFilter);
        }

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

        $revertedOrder = Order::find($request->input("id"));

        // recalculate courier's profit
        Event::fire(new TravelProfitChanged($revertedOrder->travel));
        
        // revert order status
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
            "action" => "Payment for #". $orderID,
            "code" => "REVERT"
        ]);

        $generalTransaction->save();
    }

    public function getTotalTransactionUntilDate(Request $request) {

        $dateFilter = $request->input("dateFilter");

        if (empty($request->input("dateFilter"))) {
            $dateFilter = date("Y-m-d");
        }

        $total = GeneralTransaction::where(
            DB::raw("DATE(created_at)"), "<=", $dateFilter)->sum("movement");

        return new JsonResponse(["total" => number_format($total, 0, ",", ".")]);
    }
}
