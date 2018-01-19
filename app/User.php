<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the Notes that belong to this User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany('App\Note');
    }

    /**
     * Get the User's Centre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function centre()
    {
        return $this->belongsTo('App\Centre');
    }

    /**
     * Get the relevant centres for this User, accounting for it's role
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function relevantCentres()
    {
        // default to empty collection
        $centres = collect();
        switch ($this->role) {
            case "FM_User":
                // Just get all centres
                $centres = Centre::all();
                break;
            case "CC_User":
                // If we have one, get our centre's neighbors
                if (!is_null($this->centre)) {
                    $centres = $this->centre->neighbors;
                }
                break;
        }
        return $centres;
    }

    // ToDo : Principle of Responsibility - Does this live here or on the Centre model?
    /**
     * Is a given centre relevant to this User?
     *
     * @param Centre $centre
     * @return bool
     */
    public function isRelevantCentre(Centre $centre)
    {
        return $this->relevantCentres()->contains('id', $centre);
    }
}
