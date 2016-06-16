<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use SplEnum;

class MessageOwnedByUser extends Model
{
    protected $table = "message_user";

    protected $casts = [
        "status" => "boolean"
    ];

    public function scopeOwner($query) {
        return $query->where("receiver", Auth::user()->id);
    }

    public function scopeNewest($query) {
        return $query->where("status", MessageStatus::Unread)
            ->orderBy("id", "DESC");
    }

    public function message() {
        return $this->belongsTo('App\Message', 'message_id');
    }
}

abstract class MessageStatus {
    const Unread = 0;
    const Read = 1;
}