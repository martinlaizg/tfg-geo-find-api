<?php

namespace App\Http\Controllers;

use Monolog\Logger;

use App\Map;
use App\Location;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function search(Request $request)
    {
		$maps = Map::all();
		$locations = Location::all();

		return response()->json(['maps'=>$maps, 'locations'=>$locations]);
    }

}
