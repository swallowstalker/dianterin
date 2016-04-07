<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use DisableUpdatedAt;

    protected $table = "order_feedback";

    protected $fillable = ["order_id", "feedback"];
    
}
