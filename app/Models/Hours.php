<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Hours extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];

    public function station()
    {
        return $this->belongsTo('App\Models\Station');
    }

}
