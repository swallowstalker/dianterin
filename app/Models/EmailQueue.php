<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailQueue extends Model
{
    protected $table = "email_queue";

    protected $fillable = ["destination_name", "destination_email", "subject", "content", "sent"];
}
