<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
