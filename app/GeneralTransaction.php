<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralTransaction extends Model
{
    use DisableUpdatedAt;

    protected $table = "transaction_general";

    protected $fillable = ["author_id", "user_id", "movement", "action"];

    const TRANSACTION_PAY_ORDER = 1;
    const TRANSACTION_DEPOSIT = 2;
    const TRANSACTION_TRANSFER = 3;

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function owner() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function profit() {
        return $this->hasOne('App\TransactionProfit', 'general_id');
    }

    public function scopeByOwner($query, $id) {

        $query->where("user_id", $id);
        return $query;
    }
}
