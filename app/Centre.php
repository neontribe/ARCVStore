<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centre extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the Registrations for this Centre
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Registration');
    }

    /**
     * Get the Users who belong to this Centre
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function sponsor()
    {
        return $this->belongsTo('App\Sponsor');
    }
}
