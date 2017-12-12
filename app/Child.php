<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dob',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];


    protected $dates = [
        'created_at',
        'updated_at',
        'dob'
    ];

    protected $appends = [
        'entitlement',
    ];

    /**
     * Calculates and returns the age in Years and Months
     *
     * @param string $format
     * @return string
     */
    public function getAgeString($format = '%y yr, %m mo')
    {
        return $this->dob->diff(Carbon::now())->format($format);
    }

    /**
     * Returns the DoB as a string
     *
     * @param string $format
     * @return string
     */
    public function getDobAsString($format = 'M Y')
    {
        return $this->dob->format($format);
    }

    /**
     * Calculates the School start date for a Child
     * If a Child is born before september, 4 years ahead
     * Else 5 years ahead
     *
     * @return Carbon
     */
    public function calcSchoolStart()
    {
        // september is month 9
        if ($this->dob->month < 9) {
            $years = 4;
        } else {
            $years = 5;
        }
        $school_year = $this->dob->addYears($years)->year;
        return Carbon::createFromDate($school_year, 9)->startOfMonth();
    }

    /**
     * Get a number that can be used to produce status related things
     * 0 = pregnant, no vouchers (family gets vouchers, not kids)
     * 1 = above 1, under school age
     * 2 = under 1.
     *
     * These can be used in voucher multipliers
     *
     * @return int|null
     */

    public function getStatus()
    {
        $today = Carbon::today();

        if (!$this->born) {
            // Regardless of age, if you are unborn, you count as a pregnancy
            // Even positive ages! This is a process thing
            $status = 0;
        } else {
            $is_one = $today->greaterThanOrEqualTo($this->dob->addYear());
            $started_school = $today->greaterThanOrEqualTo($this->calcSchoolStart());
            switch (true) {
                // is diff between now and dob less than one year?
                case (!$is_one):
                    // includes premature births
                    $status = 2;
                    break;
                case ( !$started_school ):
                    $status = 1;
                    break;
                default:
                    // over 4? you're not on scheme
                    $status = null;
            }
        }
        return $status;
    }

    /**
     * Convert status values to strings for Blade.
     *
     * @return mixed|string
     */
    public function getStatusString()
    {
        $scheme_codes = [
            "Pregancy",
            "Under 4 yo",
            "Under 1 yo",
        ];

        $status = $this->getStatus();

        if ($status) {
            return $scheme_codes[$status];
        } else {
            return "Unknown";
        }
    }

    /**
     * Calculates the entitlement for a child
     *
     * @return int
     */
    public function getEntitlementAttribute()
    {
        $status = $this->getStatus();
        return ($status) ? $status*3 : 0;
    }

    /**
     * Get this Child's Family.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo('App\Family');
    }
}
