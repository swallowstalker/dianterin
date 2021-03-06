<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class CourierTravelRecord extends Model
{
    protected $table = "courier_travel";

    protected $fillable = ["courier_id", "quota", "limit_time", "status"];

    const STATUS_OPENED = 1;
    const STATUS_CLOSED = 2;
    const STATUS_FINISHED = 3;
    const STATUS_NEGLECTED = 4;

    public function visitedRestaurants() {

        return $this->hasMany('App\CourierVisitedRestaurant', 'travel_id');
    }

    public function courier() {

        return $this->belongsTo('App\User');
    }

    public function orders() {
        return $this->hasMany('App\Order', 'travel_id');
    }

    public function scopeByStatus($query, $status) {

        $query->where("status", $status);
        return $query;
    }

    public function scopeByCourier($query, $courierID) {

        $query->where("courier_id", $courierID);
        return $query;
    }
}
