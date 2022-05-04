<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class StationController extends Controller
{
    public function index(Request $request)
   {
       $stations = QueryBuilder::for(Station::class)
           ->allowedIncludes(
               Station::getIncludes()
           )
           ->allowedSorts(
               Station::getSorts()
           )
           ->allowedFilters(
               Station::getFilters()
           )
           ->paginate($request->input('per_page', 25));

       return response()->json([
           'stations' => $stations
       ]);
   }
}
