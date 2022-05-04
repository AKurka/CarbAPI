<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Price extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];

    protected $hidden = ['_id', 'created_at', 'updated_at', 'id_station'];

    public function station()
    {
        return $this->belongsTo('App\Models\Station', '_id', 'id_station');
    }
}
