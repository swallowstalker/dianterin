<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = "dimenuin";

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope("active", function (Builder $builder) {
            $builder->where("status", true);
        });
    }

    public function restaurant() {
        return $this->belongsTo('App\Restaurant', 'restaurant_id');
    }

    public function scopeActive($query) {
        return $query->where("status", true);
    }

    public function getLastPreferenceAttribute($value) {
        return $value;
    }
}
