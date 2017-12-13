<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Child extends Model
{

    const NOTICE_TYPES = [
        'ChildIsAlmostOne' => ['reason' => 'child|almost 1 year old'],
        'ChildIsAlmostBorn' => ['reason' => 'child|almost born'],
        'ChildIsOverDue' => ['reason' => 'child|over due date'],
        'ChildIsAlmostSchoolAge' => ['reason' => 'child|almost school age'],
    ];

    const CREDIT_TYPES = [
        'ChildIsUnderOne' => ['reason' => 'child|under 1 yo', 'vouchers' => 3],
        'ChildIsUnderSchoolAge' => ['reason' => 'child|under school age', 'vouchers' => 3],
    ];

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
     * Get an array that indicates Notices and Credits applied to the accunt
     *
     * These can be used in voucher multipliers
     *
     * @return array
     */

    public function getStatus()
    {

        $today = Carbon::today();

        $notices = [];
        $credits = [];

        $eligibility = "Ineligible";

        if (!$this->born) {
            // Regardless of age, if you are unborn, you count as a pregnancy and get not credits
            // Even positive ages! This is a process thing

            $eligibility = "Pregnancy";

            // Calculate notices.
            $is_almost_born = ($today->diffInMonths($this->dob) < 1) && ($this->dob->isFuture());
            $is_overdue = ($today->diffInMonths($this->dob) > 1) && ($this->dob->isPast());

            switch (true) {
                case ($is_almost_born):
                    $notices[] = self::NOTICE_TYPES['ChildIsAlmostBorn'];
                    break;
                case ($is_overdue):
                    $notices[] = self::NOTICE_TYPES['ChildIsOverDue'];
                    break;
                default:
            }

        } else {
            // Setup dates
            /** @var Carbon $first_birthday */
            $first_birthday = $this->dob->addYear();
            $first_schoolday = $this->calcSchoolStart();

            // Calculate credits
            $is_one = $today->greaterThanOrEqualTo($first_birthday);
            $is_school_age = $today->greaterThanOrEqualTo($first_schoolday);

            // Calculate notices
            $is_almost_one = ($today->diffInMonths($first_birthday) < 1) && ($first_birthday->isFuture());
            $is_almost_school_age = ($today->diffInMonths($first_schoolday) < 1) && ($first_schoolday->isFuture());

            // populate notices and credits arrays.
            switch (true) {
                case ($is_almost_one):
                    $notices[] = self::NOTICE_TYPES["ChildIsAlmostOne"];
                    //
                case ($is_almost_school_age):
                    $notices[] = self::NOTICE_TYPES['ChildIsAlmostSchoolAge'];
                    //
                case (!$is_one):
                    $credits[] = self::CREDIT_TYPES["ChildIsUnderOne"];
                    //
                case (!$is_school_age):
                    $credits[] = self::CREDIT_TYPES["ChildIsUnderSchoolAge"];
            }

            if (!empty($credits)) {
                $eligibility = 'Eligible';
            }
        }

        return [
            'eligibility' => $eligibility,
            'notices' => $notices,
            'credits' => $credits,
            'vouchers' => array_sum(array_column($credits, "vouchers")),
            ];
    }

    /**
     * Get eligibility value string for Blade.
     *
     * @return mixed|string
     */
    public function getStatusString()
    {
        return $this->getStatus()['eligibility'];
    }

    /**
     * Calculates the entitlement for a child
     *
     * @return int
     */
    public function getEntitlementAttribute()
    {
        return $this->getStatus()['vouchers'];
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
