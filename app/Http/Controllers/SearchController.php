<?php

namespace App\Http\Controllers;

use App\Location;
use App\Tour;
use Illuminate\Http\Request;
use Monolog\Logger;

class SearchController extends Controller
{

    public function search(Request $request)
    {
        $log = new Logger(__CLASS__);

        $query = $request->input('q');
        $log->debug('search item = ' . $query);
        if ($query != "") {
            $tours = Tour::where('name', 'like', '%' . $query . '%')->with('creator')->get();
            $places = Location::where('name', 'like', '%' . $query . '%')->get();
        } else {
            $tours = Tour::with('creator')->get();
            $places = Location::get();
        }
        $merge = $tours->concat($places);
        return response()->json($tours);
    }

}
