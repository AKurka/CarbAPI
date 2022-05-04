<?php

namespace App\Models\Sorts\Station;

use Illuminate\Database\Eloquent\Builder;
use MatanYadaev\EloquentSpatial\Objects\Point;

class DistanceSort implements \Spatie\QueryBuilder\Sorts\Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';
        $lat = request()->input('lat');
        $lng = request()->input('lng');
        $query->orderByDistanceSphere('location', new Point($lat, $lng), $direction);
    }
}
