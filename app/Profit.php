<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    protected $table = "profit";

    protected $primaryKey = "date";

    protected $fillable = ["date", "total", "user"];

    public function setUpdatedAt($value)
    {
        // not setting anything here.
        return $this;
    }

    public function getUpdatedAtColumn()
    {
        return null;
    }
}
