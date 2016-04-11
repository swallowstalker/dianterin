<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;

class OrderElement extends Model
{
    protected $table = "order_element";

    protected $fillable = ["restaurant", "menu", "amount", "preference"];

    public function restaurantObject() {
        return $this->hasOne('App\Restaurant', 'id', 'restaurant');
    }

    public function menuObject() {
        return $this->hasOne('App\Menu', 'id', 'menu');
    }

    public function order() {
        return $this->belongsTo('App\Order', 'order_parent_id');
    }

    public function getSubtotalAttribute() {

        return $this->menuObject->price * $this->amount;
    }

    /**
     * Retrieve order element owned by logged-in user.
     *
     * @param $query
     * @return mixed
     */
    public function scopeByOwner($query) {
        
        $query->whereHas("order", function($query) {
            $query->where("user_id", Auth::user()->id);
        });

        return $query;
    }
}