<?php

namespace App;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class TransactionOrder extends Model
{
    use DisableUpdatedAt;

    protected $table = "transaction_order";

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

    public function scopeByOwner($query, $ownerID = null) {

        if (empty($ownerID)) {
            $ownerID = Auth::user()->id;
        }

        $query->where("user_id", $ownerID);
        return $query;
    }

    public function scopeToday($query) {

        $query->where(DB::raw("DATE(created_at)"), date("Y-m-d"));
        return $query;
    }
}
