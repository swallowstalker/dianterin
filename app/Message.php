<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "messages";

    protected $fillable = ["content", "type"];

    public function users() {
        return $this->hasMany('App\MessageOwnedByUser', 'message_id');
    }

    public function scopeType($query, $type) {
        return $query->where("type", $type);
    }
}