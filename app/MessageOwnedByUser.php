<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use SplEnum;

class MessageOwnedByUser extends Model
{
    protected $table = "message_user";

    protected $fillable = ["sender", "receiver", "status"];

    protected $casts = [
        "status" => "boolean"
    ];

    public function scopeOwner($query) {
        return $query->where("receiver", Auth::user()->id);
    }

    public function scopeNewest($query) {
        return $query->where("status", UserMessageStatus::Unread)
            ->orderBy("id", "DESC");
    }

    public function scopeType($query, $type) {
        return $query->whereHas("message", function ($query) use ($type) {
            $query->where("type", $type);
        });
    }

    public function message() {
        return $this->belongsTo('App\Message', 'message_id');
    }
}

abstract class UserMessageStatus {
    const Unread = 0;
    const Read = 1;
}