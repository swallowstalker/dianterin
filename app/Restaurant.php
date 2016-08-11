<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Log;

class Restaurant extends Model
{
    protected $table = "direstoranin";

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope("active", function (Builder $builder) {
            $builder->where("status", true);
        });
    }

    public function scopeOpenAt($query, $time) {

        return $query->where("open", "<=", $time)
            ->where("close", ">=", $time);
    }

    public function scopeHavingActiveCourier($query, $active = true) {

        if ($active) {
            $query->whereHas("travels", function ($query) {
                $query->where("status", CourierTravelRecord::STATUS_OPENED);
            });
        } else {
            $query->whereDoesntHave("travels", function ($query) {
                $query->where("status", CourierTravelRecord::STATUS_OPENED);
            });
        }

        return $query;
    }

    public function menus() {

        return $this->hasMany('App\Menu');
    }

    public function travels() {
        return $this->belongsToMany(
            'App\CourierTravelRecord',
            'courier_restaurant',
            'allowed_restaurant',
            'travel_id'
        );
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
