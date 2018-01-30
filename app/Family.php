<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Family extends Model
{

    // This has a | in the reason field because we want to carry the entity with it.
    const CREDIT_TYPES = [
        'FamilyIsPregnant' => ['reason' => 'family|pregnant', 'vouchers' => 3],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rvid', 'leaving_on', 'leaving_reason',
    ];

    /**
     * The attributes that are cast as dates.
     *
     * @var array
     */
    protected $dates = [
        'leaving_on',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Attributes to autocalculate and add when we ask.
     *
     * @var array
     */
    protected $appends = [
        'entitlement',
        'expecting',
    ];

    /**
     * Rules for validation. Can't provide in a static array because getting config array.
     */
    public function rules() {
        return [
            'leaving_on' => [
                'required_with:leaving_reason',
                'datetime'
            ],
            'leaving_reason' => [
                'required_with:leaving_on',
                Rule::in(config('arc.leaving_reasons')),
            ],
        ];
    }

    /**
     * Fetches the
     * Credits
     * Notices
     * Vouchers
     *
     * From children
     * and appends it's own if criteria matches
     * @return array
     */
    public function getStatus()
    {
        $notices=[];
        $credits=[];

        foreach ($this->children as $child) {
            $child_status = $child->getStatus();
            $notices = array_merge($notices, $child_status['notices']);
            $credits = array_merge($credits, $child_status['credits']);
        }

        if ($this->expecting) {
            $credits[] = self::CREDIT_TYPES['FamilyIsPregnant'];
        }

        return [
            'credits' => $credits,
            'notices' => $notices,
            'vouchers' => array_sum(array_column($credits, "vouchers")),
        ];
    }

    /**
     * Creates an array that Blade can use to publish reasons for voucher credits
     *
     * @return array
     */
    public function getCreditReasons()
    {
        $credit_reasons = [];
        $credits = $this->getStatus()["credits"];

        // get distinct reasons and frequency.
        $reason_count = array_count_values(array_column($credits, 'reason'));

        foreach ($reason_count as $reason => $count) {
            // Filter the raw credits by reason
            // create an array of the 'vouchers' column for that
            // sum that column.
            $reason_vouchers = array_sum(
                array_column(
                    array_filter(
                        $credits,
                        function ($credit) use ($reason) {
                            return $credit['reason'] == $reason;
                        }
                    ),
                    'vouchers'
                )
            );

            /*
             * Each element used by Blade in the format
             * $voucher_sum for $reason_count $entity $reason
             */
            $credit_reasons[] = [
                "entity" => explode('|', $reason)[0],
                "reason" => explode('|', $reason)[1],
                "count" => $count,
                "reason_vouchers" => $reason_vouchers,
            ];
        }

        return $credit_reasons;
    }

    /**
     * Creates an array that Blade can use to publish reasons for voucher notices
     *
     * @return array
     */
    public function getNoticeReasons()
    {
        $notice_reasons = [];
        $notices = $this->getStatus()["notices"];

        // get distinct reasons and frequency.
        $reason_count = array_count_values(array_column($notices, 'reason'));

        foreach ($reason_count as $reason => $count) {
            /*
             * Each element used by Blade in the format
             */
            $notice_reasons[] = [
                "entity" => explode('|', $reason)[0],
                "reason" => explode('|', $reason)[1],
                "count" => $count,
            ];
        }

        return $notice_reasons;
    }

    /**
     * Calculates the entitlement Attribute
     *
     */
    public function getEntitlementAttribute()
    {
        // TODO: continue to resist urge to use a rules engine or a specification pattern
        return $this->getStatus()['vouchers'];
    }

    /**
     * Gets the due date or Null;
     *
     * @return mixed
     */
    public function getExpectingAttribute()
    {
        $due = null;
        foreach ($this->children as $child) {
            if (!$child->born) {
                $due = $child->dob;
            }
        }
        return $due;
    }

    /**
     * Attribute that gets the number of eligible children
     *
     * @return integer|null
     */
    public function getEligibleChildrenCountAttribute()
    {
        return $this->children->reduce(function ($count, $child) {
            $count += ($child->getStatus()['eligibility'] == "Eligible") ? 1 : 0;
            return $count;
        });
    }

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
