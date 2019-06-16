<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    /**
     * Fillable properties
     */
    protected $fillable = [
        'name', 'description', 'image', 'order', 'lat', 'lon',
    ];

    /**
     * Hidden properties
     */
    protected $hidden = [
        'pivot',
    ];

    /**
     * Date properties
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Relation to be touched on update
     */
    protected $touches = ['tour'];

    public function tour()
    {
        return $this->belongsTo('App\Tour');
    }

    public function plays()
    {
        return $this->belongsToMany('App\Play')->withTimestamps();
    }
}
