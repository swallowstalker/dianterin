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
}

abstract class MessageType {
    const NotificationBar = 0;
    const Popup = 1;
}
