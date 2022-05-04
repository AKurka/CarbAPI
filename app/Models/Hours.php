<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Hours extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];

    protected $hidden = ['_id', 'id_station', 'created_at', 'updated_at'];

    public function station()
    {
        return $this->belongsTo('App\Models\Station');
    }

}
