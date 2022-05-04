<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Station extends Model
{
    protected $connection = 'mongodb';

    protected $guarded = [];

    protected $hidden = ['_id', 'created_at', 'updated_at'];

    public function hour()
    {
        return $this->hasOne(Hours::class, 'id_station', '_id');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\Price', 'id_station', '_id');
    }
}
