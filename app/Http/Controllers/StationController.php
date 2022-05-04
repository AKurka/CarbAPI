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
           ->allowedIncludes(['hour', 'prices'])
           ->allowedSorts(['pc'])
           ->paginate($request->input('per_page', 15));

       return response()->json([
           'stations' => $stations
       ]);
   }
}
