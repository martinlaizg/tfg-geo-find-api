<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
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
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        // 'password',
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

    public function locations()
    {
        return $this->hasMany('App\Location');
    }

}
