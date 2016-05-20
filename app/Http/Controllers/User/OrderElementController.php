<?php

namespace App\Http\Controllers\User;

use App\Events\OrderNotReceived;
use App\Events\OrderReceived;
use App\Events\ProfitChanged;
use App\Feedback;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\User\NewOrderRequest;
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

class OrderElementController extends Controller
{
    public function delete() {

        //@todo delete backup by order
        //@todo delete only backup whose parent order has "ordered" status

        //@todo if all element is deleted, delete the order too
    }
    
    public function add() {

        //@todo add only on non new order

    }
}
