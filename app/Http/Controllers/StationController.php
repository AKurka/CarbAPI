<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function show()
   {
       return response()->json([
           'stations' => Station::get()
       ]);
   }
}
