<?php

namespace App\Models;


use App\Models\Sorts\Station\DistanceSort;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;

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
        return $this->hasOne(Hour::class, 'station_id', 'id');
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'station_id', 'id');
    }

    public static function getFilters(): array
    {
        return [
            AllowedFilter::exact('zipcode'),
            AllowedFilter::scope('max_distance')
        ];
    }

    public static function getIncludes(): array
    {
        return [
            'hours',
            'prices'
        ];
    }

    public static function getSorts(): array
    {
        return [
            'zipcode',
            AllowedSort::custom('distance', new DistanceSort(), 'distance')
        ];
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
