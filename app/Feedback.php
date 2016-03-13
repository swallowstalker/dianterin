<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = "order_feedback";

    protected $fillable = ["order_id", "feedback"];


    public function setUpdatedAt($value)
    {
        // not setting anything here.
        return $this;
    }
}
