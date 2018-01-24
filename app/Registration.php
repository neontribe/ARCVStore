<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    const REMINDER_TYPES = [
        'FoodDiaryNeeded' => ['reason' => 'Food Diary|not been received'],
        'FoodChartNeeded' => ['reason' => 'Food Chart|not been received'],
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'eligibility',
        'consented_on',
        'fm_chart_on',
        'fm_diary_on',
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

    public function getStatus()
    {
        $reminders = [];

        if (!$this->fm_chart_on) {
            $reminders[] = self::REMINDER_TYPES['FoodChartNeeded'];
        }
        if (!$this->fm_diary_on) {
            $reminders[] = self::REMINDER_TYPES['FoodDiaryNeeded'];
        }

        return $reminders;
    }

    public function getReminderReasons()
    {
        $reminder_reasons = [];

        $reminders = $this->getStatus();

        // get distinct reasons and frequency.
        $reason_count = array_count_values(array_column($reminders, 'reason'));

        foreach ($reason_count as $reason => $count) {
            /*
             * Each element used by Blade in the format
             */
            $reminder_reasons[] = [
                "entity" => explode('|', $reason)[0],
                "reason" => explode('|', $reason)[1],
                "count" => $count,
            ];
        }
        return $reminder_reasons;
    }
}
