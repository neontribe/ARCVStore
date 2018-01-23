<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    const NOTICE_TYPES = [
        'CheckFoodDiaryUnchecked' => ['reason' => 'Food Diary|has been received'],
        'CheckFoodDiary' => ['reason' => 'Food Diary|Acknowledged'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'eligibility',
        'consented_on',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * Get the Registration's Family
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function family()
    {
        return $this->belongsTo('App\Family');
    }

    /**
     * Get the Registration's Centre
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function centre()
    {
        return $this->belongsTo('App\Centre');
    }

    public function getReminderReasons() {
        $reminder_reasons = [];
        $reminders = $this->getStatus()["reminders"];

        // get distinct reasons and frequency.
        $reason_count = array_count_values(array_column($reminders, 'reason'));

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
        return $reminder_reasons;
    }

}
