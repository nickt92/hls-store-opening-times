<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;

class ShopClosure extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description', 'start', 'finish', 'reoccurring', 'enabled'
    ];

    /**
     * Get all closures based on provided DateTime
     *
     * @param DateTime $dt
     * @return object
     */
    public static function getClosures(DateTime $dt = null)
    {
        if (!$dt instanceof DateTime) {
            $dt = new DateTime('now');
        }

        $dateToFilter = $dt->format('Y-m-d H:i:s');

        $closuresCollection = ShopClosure::where([
            ['start', '>=', $dateToFilter],
            ['finish', '>=', $dateToFilter],
            ['enabled', '=', true]
        ]);

        return $closuresCollection;
    }
}
