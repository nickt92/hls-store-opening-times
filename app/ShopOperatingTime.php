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
}
