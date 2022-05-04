<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Sorts\Station\DistanceSort;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class StationController extends Controller
{
    public function index(Request $request)
   {
       $stations = QueryBuilder::for(Station::class)
           ->allowedIncludes(['hours', 'prices'])
           ->allowedSorts([
               'pc',
               AllowedSort::custom('distance', new DistanceSort(), 'distance')
           ])
           ->allowedFilters([
               AllowedFilter::exact('zipcode'),
               AllowedFilter::scope('max_distance'),
           ])
           ->paginate($request->input('per_page', 25));

       return response()->json([
           'stations' => $stations
       ]);
   }
}
