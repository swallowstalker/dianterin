<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class CourierVisitedRestaurant extends Model
{
    use DisableUpdatedAt, DisableCreatedAt;

    protected $table = "courier_restaurant";

    protected $fillable = ["travel_id", "allowed_restaurant", "delivery_cost"];

    public function restaurant() {
        return $this->belongsTo('App\Restaurant', "allowed_restaurant");
    }
}
