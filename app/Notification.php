<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Log;

class Notification extends Model
{
    use DisableUpdatedAt;

    protected $table = "notification";

    protected $fillable = ["sender", "receiver", "status", "message"];

    public function scopeActive($query) {
        return $query->where("status", false);
    }

    public function scopeByOwner($query) {
        return $query->where("receiver", Auth::user()->id);
    }

    public function scopeByNewest($query) {
        return $query->orderBy("created_at", "desc");
    }
}
