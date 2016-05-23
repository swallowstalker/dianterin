<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class CourierVisitedRestaurant extends Model
{
    use DisableUpdatedAt, DisableCreatedAt;

    protected $table = "courier_restaurant";

    protected $fillable = ["travel_id", "allowed_restaurant", "delivery_cost"];

    public function travel() {
        return $this->belongsTo('App\CourierTravelRecord', 'travel_id');
    }
}
