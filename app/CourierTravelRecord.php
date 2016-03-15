<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class CourierTravelRecord extends Model
{
    protected $table = "courier_travel";

    public function visitedRestaurants() {

        return $this->hasMany('App\CourierVisitedRestaurant', 'travel_id');
    }

    public function courier() {

        return $this->belongsTo('App\User');
    }

    public function scopeIsOpen($query) {

        $query->where("limit_time", ">", DB::raw("NOW()"));
        return $query;
    }
}
