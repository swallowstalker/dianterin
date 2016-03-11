<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Log;

class UserTransaction extends Model
{
    protected $table = "transaction_general";

    public function scopeByOwner($query) {

        $query->where("user_id", Auth::user()->id);
        return $query;
    }
}
