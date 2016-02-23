<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Log;

class Restaurant extends Model
{
    protected $table = "direstoranin";

    /**
     * Filter active restaurant
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query) {

        return $query->where("status", 1);
    }

    public function menus() {

        return $this->hasMany('App\Menu');
    }

    public function getMaxPriceAttribute() {

        $priceList = $this->menus->pluck("price");
        return $priceList->max();
    }

    public function getMinPriceAttribute() {

        $priceList = $this->menus->pluck("price");
        return $priceList->min();
    }

    public function getTotalMenuAttribute() {

        return $this->menus->count();
    }
}
