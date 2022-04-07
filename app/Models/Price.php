<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Price extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];

    public function station()
    {
        return $this->belongsTo('App\Models\Station', '_id', 'station_id');
    }
}
