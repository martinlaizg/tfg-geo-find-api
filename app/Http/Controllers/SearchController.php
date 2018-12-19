<?php

namespace App\Http\Controllers;

use Monolog\Logger;

use App\Map;
use App\Location;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SearchController extends Controller
{

	function __construct(){
	}

    public function search(Request $request)
    {
		$log = new Logger(__CLASS__);

		$query = $request->input('q');
		$log->debug('search item = '.$query);
		if($query != ""){
			$maps = Map::where('name', 'like', '%'.$query.'%')->with('creator')->get();
			$locations = Location::where('name', 'like', '%'.$query.'%')->get();
		} else {
			$maps = Map::with('creator')->get();
			$locations = Location::get();
		}
		$merge = $maps->concat($locations);
		return response()->json($maps);
    }

}
