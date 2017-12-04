<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function registrations()
    {
        return $this->hasMany('App\Registration');
    }

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function sponsor()
    {
        return $this->belongsTo('App\Sponsor');
    }
}
