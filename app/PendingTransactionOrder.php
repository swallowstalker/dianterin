<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class PendingTransactionOrder extends TransactionOrder
{
    protected $table = "transaction_order_pending";
}
