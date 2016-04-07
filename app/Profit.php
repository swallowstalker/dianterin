<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profit extends Model
{
    use DisableUpdatedAt;

    protected $table = "profit";

    protected $primaryKey = "date";

    protected $fillable = ["date", "total", "user"];
}
