<?php

namespace App;

use Carbon\Carbon;
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

    public function scopeOpenAt($query, $time) {

        return $query->where("open", "<=", $time)
            ->where("close", ">=", $time);
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

    public function getImageAttribute($value) {

        $image = "img_default_restaurant.png";

        //@todo change IMAGE_LOCATION, using folder linking instead
        if (! empty($value) && file_exists(base_path(env("IMAGE_LOCATION") .
                "img/restaurant/". $value))) {

            $image = "restaurant/". $value;
        }

        return $image;

    }

    public function getOpenAttribute($value) {
        return Carbon::createFromFormat("H:i:s", $value, "Asia/Jakarta");
    }

    public function getCloseAttribute($value) {
        return Carbon::createFromFormat("H:i:s", $value, "Asia/Jakarta");
    }

    public function getOpenStatusAttribute($value) {

        $now = Carbon::now("Asia/Jakarta")->getTimestamp();

        if ($this->open->getTimestamp() <= $now && $now <= $this->close->getTimestamp()) {
            return true;
        } else {
            return false;
        }
    }
}
