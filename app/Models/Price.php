<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $guarded = [];

    protected $hidden = ['id', 'created_at', 'updated_at', 'station_id'];

    public function station()
    {
        return $this->belongsTo(Station::class, '_id', 'station_id');
    }
}
