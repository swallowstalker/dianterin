<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class PendingTransactionOrder extends Model
{
    protected $table = "transaction_order_pending";

    protected $fillable = [
        "user_id",
        "order_id",
        "restaurant",
        "menu",
        "price",
        "delivery_cost",
        "adjustment",
        "adjustment_info",
        "final_cost",
    ];

    public function scopeByOwner($query) {

        $query->where("user_id", Auth::user()->id);
        return $query;
    }

    public function setUpdatedAt($value)
    {
        // not setting anything here.
        return $this;
    }
}
