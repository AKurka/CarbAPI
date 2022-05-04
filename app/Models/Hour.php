<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Hour extends Model
{
    protected $guarded = [];

    protected $hidden = ['id', 'station_id', 'created_at', 'updated_at'];

    public function station()
    {
        return $this->belongsTo('App\Models\Station');
    }

}
