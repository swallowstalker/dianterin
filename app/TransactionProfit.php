<?php

namespace App;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;

class TransactionProfit extends Model
{
    public $timestamps = false;

    protected $table = "transaction_order";

    protected $fillable = [
        "general_id",
        "travel_id"
    ];

    public function generalTransaction() {
        return $this->belongsTo('App\GeneralTransaction', 'general_id');
    }

    public function travel() {
        return $this->belongsTo('App\CourierTravelRecord', 'travel_id');
    }
}
