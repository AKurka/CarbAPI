<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Station extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];

    public function hours()
    {
        return $this->hasMany('App\Models\Hour', 'station_id', '_id');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\Price', 'station_id', '_id');
    }
}
