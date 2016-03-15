<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Log;


define('ITEM_ORDERED', 0);
define('ITEM_PROCESSED', 1);

define('ITEM_DELIVERED', 4);
define('ITEM_NOT_RECEIVED', 5);

define('ITEM_RECEIVED_BY_FORCE', 6);
define('ITEM_RECEIVED', 2);
define('ITEM_NOT_FOUND', 3);

class Order extends Model
{
    protected $table = "order_parent";

    public static $statusDescriptionList = [
        0 => "Ordered",
        1 => "Processed",
        4 => "Delivered",

        5 => "Not Received",
        6 => "Received By Force",
        2 => "Received",
        3 => "Not Found"
    ];

    const STATUS_ORDERED = 0;
    const STATUS_PROCESSED = 1;

    const STATUS_DELIVERED = 4;
    const STATUS_NOT_RECEIVED = 5;

    const STATUS_RECEIVED_BY_FORCE = 6;
    const STATUS_RECEIVED = 2;
    const STATUS_NOT_FOUND = 3;

    protected $fillable = ["travel_id", "user_id", "status"];

    /**
     * Sub element of this order.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function elements() {
        return $this->hasMany('App\OrderElement', 'order_parent_id');
    }

    public function travel() {
        return $this->hasOne('App\CourierTravelRecord', 'travel_id');
    }

    /**
     * Retrieve order owned by logged-in user.
     *
     * @param $query
     * @return mixed
     */
    public function scopeByOwner($query) {

        $query->where("user_id", Auth::user()->id);
        return $query;
    }

    public function scopeByStatus($query, $status) {

        $query->where("status", $status);
        return $query;
    }
}
