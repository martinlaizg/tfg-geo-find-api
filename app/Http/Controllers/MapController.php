<?php

namespace App\Http\Controllers;

use App\Map;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Monolog\Logger;

class MapController extends Controller
{

    public function getCoordWithMargin($userCoord)
    {
        $margin = 100;
        return [$userCoord - $margin, $userCoord + $margin];
    }

    public function getAll(Request $request)
    {
        $log = new Logger("MapController");
        $coords = $request->input('coords');
        $maps = Map::query();
        if ($coords != "") {
            $log->debug('coords=' . $coords);
            $userLat = strtok($coords, ',');
            $userLon = strtok(',');
            $lat = $this->getCoordWithMargin($userLat);
            $lon = $this->getCoordWithMargin($userLon);
            $log->debug('lat=' . $userLat . '; range=(' . implode(',', $lat) . ')');
            $log->debug('lon=' . $userLon . '; range=(' . implode(',', $lon) . ')');

            $maps = $maps->whereHas('locations', function ($query) use ($lat, $lon) {
                $query->whereBetween('lat', $lat)->whereBetween('lon', $lon);
            });
        }
        $creator = $request->input('creator');
        if ($creator != "") {
            $creator_id = User::where('username', 'like', '%' . $creator)
                ->orWhere('username', 'like', $creator . '%')
                ->orWhere('username', 'like', '%' . $creator . '%')
                ->orWhere('name', 'like', $creator . '%')
                ->orWhere('name', 'like', '%' . $creator)
                ->orWhere('name', 'like', '%' . $creator . '%')->first()->id;
            $log->debug('creator=' . $creator);
            $log->debug('creator_id=' . $creator_id);
            $maps = $maps->where('creator_id', $creator_id);
        }
        $search = $request->input('q');
        if ($search != "") {
            $log->debug('search=' . $search);
            $maps = $maps
                ->where('name', 'like', '%' . $search)
                ->orWhere('name', 'like', $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('country', 'like', $search . '%')
                ->orWhere('country', 'like', '%' . $search)
                ->orWhere('country', 'like', '%' . $search . '%')
                ->orWhere('state', 'like', $search . '%')
                ->orWhere('state', 'like', '%' . $search)
                ->orWhere('state', 'like', '%' . $search . '%')
                ->orWhere('city', 'like', $search . '%')
                ->orWhere('city', 'like', '%' . $search)
                ->orWhere('city', 'like', '%' . $search . '%');
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
