<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'username', 'password', 'name', 'bdate', 'user_type',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'pivot',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'bdate',
    ];

    /**
     * Get the user created tours
     */
    public function createdTours()
    {
        // Second paramater because Eloquent search 'createdTours_id' on tours table
        return $this->hasMany('App\Tour', 'creator_id');
    }

    public function tours()
    {
        return $this->belongsToMany('App\Tour', 'plays', 'user_id', 'tour_id')->withTimestamps();
    }

    public function socials()
    {
        return $this->hasMany('App\Social');
    }

}
