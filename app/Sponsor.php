<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
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


    /**
     * Get the Sponsor's Centres
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function centres()
    {
        return $this->hasMany('App\Centre');
    }
}
