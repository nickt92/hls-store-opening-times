<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopOperatingWeekday extends Model
{
    /**
     * Set has-many relationship for weekdays -> operating times
     * 
     * @return void
     */
    public function operatingTimes()
    {
        return $this->hasMany('App\ShopOperatingTime');
    }
}
