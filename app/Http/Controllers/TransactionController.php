<?php

namespace App\Http\Controllers;

use App\Order;
use App\PendingTransactionOrder;
use App\TransactionOrder;
use Auth;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TransactionController extends Controller
{

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show user transaction history
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history() {

        $viewData = $this->getOrderSidebar();

        return view("public.transaction.history", $viewData);
    }

    /**
     * Get DT data for transaction history
     *
     * @return mixed
     */
    public function data() {

        $history = TransactionOrder::where("user_id", Auth::user()->id)
            ->orderBy("created_at", "desc")->get();

        $transactionDate = '{!!
            Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $created_at)
            ->toFormattedDateString();
            !!}';

        $orderedMenu = '{{ $restaurant .", ". $menu }}';



        return Datatables::of($history)
            ->addColumn("transaction_date", $transactionDate)
            ->addColumn("ordered_menu", $orderedMenu)
            ->make(true);
    }

    /**
     * Get order list / pending transaction for order sidebar.
     * @return array
     */
    private function getOrderSidebar() {

        $viewData = [];

        $viewData["orderedList"] = Order::byOwner()->byStatus(Order::STATUS_ORDERED)->get();
        $viewData["processedList"] = Order::byOwner()->byStatus(Order::STATUS_PROCESSED)->get();

        $viewData["pendingTransactionList"] = PendingTransactionOrder::byOwner()->get();

        return $viewData;
    }
}
