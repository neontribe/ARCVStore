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
        'rvid',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Spits out a pseudorandom string to ue an RVID
     *
     * @param int $l
     * @return string
     */
    public static function generateRVID($l = 8)
    {
        //TODO: make a better guid function that produces human usable strings
        //TODO: validate this is unique against the DB
        $str = "";
        for ($x=0; $x<$l; $x++) {
            $str .= substr(str_shuffle("2346789BCDFGHJKMPQRTVWXY"), 0, 1);
        }
        return "RV-".$str;
    }

    /**
     * Get the Family's designated Carers
     * There should always be ONE of these!
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carers()
    {
        return $this->hasMany('App\Carer');
    }

    /**
     * Get the Family's Children
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany('App\Child');
    }

    /**
     * Get Notes about this Family
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany('App\Note');
    }

    /**
     * Get the Registrations with Centres for this Family
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function registrations()
    {
        return $this->hasMany('App\Registration');
    }
}
