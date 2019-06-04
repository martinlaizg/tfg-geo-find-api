<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'title', 'message',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
