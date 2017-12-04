<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Carer extends Model
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

    public function family()
    {
        return $this->belongsTo('App\Family');
    }
}
