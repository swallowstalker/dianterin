<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GeneralTransaction extends Model
{
    protected $table = "transaction_general";

    protected $fillable = ["author_id", "user_id", "movement", "action"];

    public function setUpdatedAt($value)
    {
        // not setting anything here.
        return $this;
    }
}
