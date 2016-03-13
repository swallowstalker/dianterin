<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class PendingTransactionOrder extends Model
{
    protected $table = "transaction_order_pending";

    public function scopeByOwner($query) {

        $query->where("user_id", Auth::user()->id);
        return $query;
    }
}
