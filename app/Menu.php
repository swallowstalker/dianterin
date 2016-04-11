<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "dimenuin";

    public function restaurant() {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }

    public function getLastPreferenceAttribute($value) {
        
        return $value;
    }
}
