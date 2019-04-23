<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Play extends Model
{
    protected $hidden = [
        'tour_id', 'user_id', 'pivot',
    ];

    protected $fillable = [
        'tour_id', 'user_id',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function places()
    {
        return $this->belongsToMany('App\Place')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function tour()
    {
        return $this->belongsTo('App\Tour');
    }

}
