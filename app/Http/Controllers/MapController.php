<?php

namespace App\Http\Controllers;

use App\Location;
use App\Map;
use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Monolog\Logger;
use Validator;

class MapController extends Controller
{

    public function getCoordWithMargin($userCoord)
    {
        $margin = 10;
        return [$userCoord - $margin, $userCoord + $margin];
    }

    public function getAll(Request $request)
    {
        try {
            $log = new Logger('MapController - Get maps');
            $coords = $request->input('coords');
            $maps = Map::query();
            if ($coords != '') {
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
            if ($creator != '') {
                $creator_id = (int) $creator;
                $log->debug('creator_id=' . $creator_id);
                if ($creator_id == 0) {
                    try {
                        $creator_id = User::where('username', 'like', '%' . $creator)
                            ->orWhere('username', 'like', $creator . '%')
                            ->orWhere('username', 'like', '%' . $creator . '%')
                            ->orWhere('name', 'like', $creator . '%')
                            ->orWhere('name', 'like', '%' . $creator)
                            ->orWhere('name', 'like', '%' . $creator . '%')->first()->id;
                        $log->debug('new_creator_id=' . $creator_id);
                    } catch (Exception $e) {
                        $log->debug('exception catched');
                        $creator_id = 0;
                    }
                }
                if ($creator_id > 0) {
                    $maps = $maps->where('creator_id', $creator_id);
                }
            }
            $search = $request->input('q');
            if ($search != '') {
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
            return response()->json($maps->with(['creator', 'locations' => function ($query) {
                $query->orderBy('position');
            }])->get(), 200);

        } catch (QueryException $e) {
            return response()->json(['error' => 1, 'message' => $e->getMessage()]);
        }
    }

    public function get($id)
    {
        $log = new Logger('MapController - Get by id');
        $log->debug('map_id=' . $id);
        // $map = Map::find($id)->with('creator')->with('locations')->first();
        $map = Map::find($id);
        return response()->json($map);
    }

    public function create(Request $request)
    {
        $log = new Logger('MapController - Create Map');
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'min_level' => 'required',
            'creator_id' => 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            if ($validator->fails()) {
                return response()->json([
                    'type' => $validator->errors()->keys()[0],
                    'message' => $validator->errors()->first(),
                ], 401);
            }
        }
        $map = new Map;
        $map->name = $request->input('name');
        $map->description = $request->input('description');
        $map->min_level = $request->input('min_level');
        $map->image = $request->input('image') != null ? $request->input('image') : "";
        $creator = User::find($request->input('creator_id'));
        $creator->createdMaps()->save($map);
        $log->debug($map);
        return response()->json($map);
    }

    public function update($id, Request $request)
    {
        $log = new Logger(__CLASS__ . __METHOD__);
        $log->debug($request);
        if (count(Map::where('id', $id)->get()) == 0) {
            return response()->json([
                'type' => 'id',
                'message' => 'The map no exist',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'min_level' => 'required',
        ]);
        if ($validator->fails()) {
            if ($validator->fails()) {
                return response()->json([
                    'type' => $validator->errors()->keys()[0],
                    'message' => $validator->errors()->first(),
                ], 401);
            }
        }

        $map = Map::find($id);
        $map->name = $request->input('name');
        $map->description = $request->input('description');
        $map->min_level = $request->input('min_level');
        $map->save();
        $log->debug('new map = ' . $map);
        return response()->json($map, 200);
    }

    public function delete($id)
    {
        Map::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }

    public function getLocations($id)
    {
        $log = new Logger(__CLASS__ . __METHOD__);
        $log->debug('map_id:' . $id);
        $map = Map::find($id);
        if ($map == null) {
            return response()->json([
                'type' => 'id',
                'message' => 'The map_id not exist',
            ], 404);
        }

        $locs = $map->locations()->orderBy('position')->get();
        return response()->json($locs);
    }

    public function createLocations($id, Request $request)
    {
        $log = new Logger(__CLASS__ . __METHOD__);
        $log->debug('map_id:' . $id);

        $map = Map::find($id);
        if ($map == null) {
            return response()->json([
                'type' => 'id',
                'message' => 'The map no exist',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            '*.name' => 'required|string',
            '*.description' => 'required|string',
            '*.lat' => 'required|numeric',
            '*.lon' => 'required|numeric',
            '*.position' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($validator->fails()) {
                return response()->json([
                    'type' => $validator->errors()->keys()[0],
                    'message' => $validator->errors()->first(),
                ], 401);
            }
        }

        DB::transaction(function () use ($map, $request, $log) {
            foreach ($request->json() as $row) {
                $loc = new Location;
                $loc->name = $row['name'];
                $loc->description = $row['description'];
                $loc->lat = $row['lat'];
                $loc->lon = $row['lon'];
                $loc->position = $row['position'];
                $map->locations()->save($loc);
            }
        });

        return response()->json($map->locations);
    }

    public function updateLocations($id, Request $request)
    {
        $log = new Logger(__CLASS__ . __METHOD__);
        $log->debug('map_id:' . $id);
        $log->debug($request);

        $map = Map::find($id);
        if ($map == null) {
            return response()->json([
                'type' => 'id',
                'message' => 'The map no exist',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            '*.id' => 'required|integer',
            '*.name' => 'required|string',
            '*.description' => 'required|string',
            '*.lat' => 'required|numeric',
            '*.lon' => 'required|numeric',
            '*.position' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($validator->fails()) {
                return response()->json([
                    'type' => $validator->errors()->keys()[0],
                    'message' => $validator->errors()->first(),
                ], 401);
            }
        }

        $map->locations()->update(['position' => null]);

        DB::transaction(function () use ($map, $request, $log) {
            foreach ($request->json() as $row) {
                $id = $row['id'];
                $loc = $map->locations()->find($id);
                $loc->name = $row['name'];
                $loc->description = $row['description'];
                $loc->lat = $row['lat'];
                $loc->lon = $row['lon'];
                $loc->position = $row['position'];
                $loc->save();
            }
        });

        return response()->json($map->locations);

    }

}
