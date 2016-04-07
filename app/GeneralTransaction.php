<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralTransaction extends Model
{
    use DisableUpdatedAt;

    protected $table = "transaction_general";

    protected $fillable = ["author_id", "user_id", "movement", "action"];

    public function author() {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function owner() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopeByOwner($query, $id) {

        $query->where("user_id", $id);
        return $query;
    }
}
