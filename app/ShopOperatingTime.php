<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
     * Set belongs-to relationship for operating times -> weekdays
     *
     * @return void
     */
    public function operatingWeekday()
    {
        return $this->belongsTo(ShopOperatingWeekday::class, 'shop_operating_weekday_id');
    }
}
