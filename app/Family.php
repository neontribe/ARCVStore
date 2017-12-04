<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
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

    public function carers()
    {
        return $this->hasMany('App\Carer');
    }

    public function children()
    {
        return $this->hasMany('App\Child');
    }

    public function notes()
    {
        return $this->hasMany('App\Note');
    }

    public function registrations()
    {
        return $this->hasMany('App\Registration');
    }
}
