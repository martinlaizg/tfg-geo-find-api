<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $fillable = [
        'lat', 'lon',
    ];

    protected $hidden = [
        'pivot',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function tour()
    {
        return $this->belongsTo('App\Tour');
    }

    public function plays()
    {
        return $this->belongsToMany('App\Play')->withTimestamps();
    }
}
