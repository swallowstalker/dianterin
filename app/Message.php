<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = "notification";

    protected $fillable = ["sender", "receiver", "status", "message"];

    public function setUpdatedAt($value)
    {
        // not setting anything here.
        return $this;
    }
}
