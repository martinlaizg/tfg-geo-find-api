<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'country', 'state', 'city', 'min_level',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Get the creator of the Map
     */
    public function creator()
    {
        return $this->belongsTo('App\User');
    }

    public function places()
    {
        return $this->hasMany('App\Place')->orderBy('order');
    }

}
