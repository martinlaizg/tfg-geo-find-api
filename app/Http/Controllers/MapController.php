<?php

namespace App\Http\Controllers;

use App\Map;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Monolog\Logger;

class MapController extends Controller
{

    public function getAll(Request $request)
    {
        $log = new Logger("MapController");
        $coords = $request->input('coords');
        $maps = Map::query();
        if ($coords != "") {
            $log->debug('coords=' . $coords);
            $userLat = strtok($coords, ',');
            $userLon = strtok(',');
            $margin = 100;
            $lat = [$userLat - $margin, $userLat + $margin];
            $lon = [$userLon - $margin, $userLon + $margin];
            $log->debug('lat=' . $userLat . '; range=(' . implode(',', $lat) . ')');
            $log->debug('lon=' . $userLon . '; range=(' . implode(',', $lon) . ')');

            $maps = $maps->whereHas('locations', function ($query) use ($lat, $lon) {
                $query->whereBetween('lat', $lat)->whereBetween('lon', $lon);
            });
        }
        $creator = $request->input('creator');
        if ($creator != "") {
            $log->debug('creator=' . $creator);
            $maps = $maps->where('creator_id', $creator);
        }

        return response()->json($maps->with('creator')->get(), 200);
    }

    public function get($id)
    {

        return response()->json(Map::find($id));
    }

    public function create(Request $request)
    {
        try {

            $map = Map::create($request->all());
            return response()->json($map, 201);

        } catch (QueryException $e) {
            return response()->json(['error' => 1, 'message' => __FUNCTION__ . "() in " . __FILE__ . " at " . __LINE__]);
        }
    }

    public function update($id, Request $request)
    {
        $map = Map::findOrFail($id);
        $map->update($request->all());

        return response()->json($map, 200);
    }

    public function delete($id)
    {
        Map::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

}
