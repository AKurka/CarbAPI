<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

class Station extends Model
{
    protected $guarded = [];

    protected $hidden = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'services' => 'array',
        'location' => Point::class
        ];

    public function hours()
    {
        return $this->hasOne(Hour::class, 'station_id', '_id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'station_id', '_id');
    }

    public function scopeMaxDistance($query, float $distance)
    {
        $lat = request()->input('lat');
        $lng = request()->input('lng');
        return $query->whereDistanceSphere('location', new Point($lat, $lng), '<=', $distance);
    }

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }
}
