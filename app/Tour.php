<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    protected $fillable = [
        'name', 'country', 'state', 'city', 'min_level',
    ];

    protected $hidden = [
        'pivot',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    public function places()
    {
        return $this->hasMany('App\Place')->orderBy('order');
    }

    public function players()
    {
        return $this->belongsToMany('App\User', 'plays', 'tour_id', 'user_id')->withTimestamps();
    }

}
