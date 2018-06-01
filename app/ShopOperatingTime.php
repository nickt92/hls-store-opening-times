<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class ShopOperatingTime extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'opening_time', 'closing_time', 'closed_message'
    ];

    /**
     * Get Stores opening times
     *
     * @param DateTime $dt
     * @return object
     */
    public static function getStoresOpeningTimes(DateTime $dt = null)
    {
        if (!$dt instanceof DateTime) {
            $dt = new DateTime('now');
        }

        $currentDayInt = $dt->format('N');
        $currentTime = $dt->format('H:i:s');

        $weekdayOpeningTimesCollection = ShopOperatingTime::where([
            ['shop_operating_weekday_id', $currentDayInt],
            ['opening_time', '<=', $currentTime],
            ['closing_time', '>=', $currentTime]
        ])->get();

        return $weekdayOpeningTimesCollection;
    }

    /**
     * Set belongs-to relationship for operating times -> weekdays
     *
     * @return void
     */
    public function operatingWeekday()
    {
        return $this->belongsTo(ShopOperatingWeekday::class, 'shop_operating_weekday_id');
    }
}
