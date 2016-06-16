<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Notification;
use App\Order;
use App\PendingTransactionOrder;
use App\TransactionOrder;
use Auth;
use Datatables;

class TransactionController extends Controller
{

    /**
     * Show user transaction history
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function history() {

        $viewData = $this->getOrderSidebar();

        $viewData["notifications"] = Notification::active()
            ->byOwner()->byNewest()->get();

        return view("public.transaction.history", $viewData);
    }

    /**
     * Get DT data for transaction history
     *
     * @return mixed
     */
    public function data() {

        $history = TransactionOrder::where("user_id", Auth::user()->id);

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

        $viewData["orderedList"] = Order::byOwner()
            ->byStatus(Order::STATUS_ORDERED)->today()->get();
        $viewData["processedList"] = Order::byOwner()
            ->byStatus(Order::STATUS_PROCESSED)->today()->get();

        $viewData["pendingTransactionList"] = PendingTransactionOrder::byOwner()
            ->today()->get();

        return $viewData;
    }
}
