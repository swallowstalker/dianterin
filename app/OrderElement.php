<?php

namespace App;

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
}