<?php

namespace App\Http\Controllers;

use App\Place;
use App\Tour;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Monolog\Logger;
use Validator;

class TourController extends Controller
{

    public function getPlaces($id)
    {
        $tour = Tour::find($id);
        return response()->json($tour->places);
    }

    public function update(Request $request, $id)
    {
        $log = new Logger(__CLASS__ . __METHOD__);

        try {
            $map = Tour::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'type' => 'id',
                'message' => 'The map no exist',
            ], 404);
        }

        // validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'min_level' => ['required', Rule::in(['map', 'compas', 'therm'])],
            'places' => 'required|array|min:1',
            'places.*.name' => 'required|string',
            'places.*.description' => 'required|string',
            'places.*.lat' => 'required|numeric',
            'places.*.lon' => 'required|numeric',
            'places.*.order' => 'required|numeric|distinct',
        ]);
        if ($validator->fails()) {
            if ($validator->fails()) {
                $array = explode('.', $validator->errors()->keys()[0]);
                return response()->json([
                    'type' => end($array),
                    'message' => $validator->errors()->first(),
                ], 401);
            }
        }

        $places = $request->input('places');
        $response = $this->updateArrayPlaces($places, $id);
        if ($response == false) {
            return response()->json([
                'type' => 'palces',
                'message' => 'Error updating places',
            ], 401);
        }

        DB::transaction(function () use ($map, $request, $log) {
            $map->places()->update(['order' => null]);
            foreach ($request->input('places') as $row) {
                $id = $row['id'];
                if ($id != null) {
                    $loc = $map->places()->find($id);
                } else {
                    $loc = new Place;
                }
                $loc->name = $row['name'];
                $loc->description = $row['description'];
                $loc->lat = $row['lat'];
                $loc->lon = $row['lon'];
                $loc->order = $row['order'];
                if ($id != null) {
                    $loc->save();
                } else {
                    $map->places()->save($loc);
                }
            }
        });
        $map->name = $request->input('name');
        $map->description = $request->input('description');
        $map->min_level = $request->input('min_level');
        $map->save();
        return response()->json($map->with(['places', 'creator'])->first(), 200);
    }

    private function getCoordWithMargin($userCoord)
    {
        $margin = 10;
        return [$userCoord - $margin, $userCoord + $margin];
    }

    public function getAll(Request $request)
    {
        try {
            $log = new Logger(__CLASS__ . __METHOD__);
            $coords = $request->input('coords');
            $maps = Tour::query();
            if ($coords != '') {
                $log->debug('coords=' . $coords);
                $userLat = strtok($coords, ',');
                $userLon = strtok(',');
                $lat = $this->getCoordWithMargin($userLat);
                $lon = $this->getCoordWithMargin($userLon);
                $log->debug('lat=' . $userLat . '; range=(' . implode(',', $lat) . ')');
                $log->debug('lon=' . $userLon . '; range=(' . implode(',', $lon) . ')');
                $maps = $maps->whereHas('places', function ($query) use ($lat, $lon) {
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
            return response()->json($maps->with(['creator', 'places' => function ($query) {
                $query->orderBy('order');
            }])->get(), 200);

        } catch (QueryException $e) {
            return response()->json(['error' => 1, 'message' => $e->getMessage()]);
        }
    }

    public function getSingleTour($id)
    {
        $log = new Logger(__CLASS__ . __METHOD__);
        $log->debug('map_id=' . $id);

        $map = Tour::where('id', $id)->with(['creator', 'places'])->first();
        if ($map == null) {
            return response()->json([
                'type' => 'id',
                'message' => 'The tour no exist',
            ], 404);
        }
        return response()->json($map);
    }

    public function create(Request $request)
    {
        $log = new Logger(__CLASS__ . __METHOD__);
        $log->debug($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'min_level' => 'required',
            'creator_id' => 'required|exists:users,id',
            'places' => 'required|array|min:1',
            'places.*.name' => 'required|string',
            'places.*.description' => 'required|string',
            'places.*.lat' => 'required|numeric',
            'places.*.lon' => 'required|numeric',
            'places.*.order' => 'required|numeric|distinct',
        ]);
        if ($validator->fails()) {
            if ($validator->fails()) {
                return response()->json([
                    'type' => $validator->errors()->keys()[0],
                    'message' => $validator->errors()->first(),
                ], 401);
            }
        }
        $map = new Tour;
        DB::transaction(function () use ($request, $map) {
            $map->name = $request->input('name');
            $map->description = $request->input('description');
            $map->min_level = $request->input('min_level');
            $map->image = $request->input('image') != null ? $request->input('image') : "";
            $creator = User::find($request->input('creator_id'));
            $creator->createdTours()->save($map);
            foreach ($request->input('places') as $row) {
                $place = new Place;
                $place->name = $row['name'];
                $place->description = $row['description'];
                $place->lat = $row['lat'];
                $place->lon = $row['lon'];
                $place->order = $row['order'];
                $map->places()->save($place);
            }
            $map->load(['creator', 'places']);
        });

        return response()->json($map);
    }

    public function updatePlaces(Request $request, $id)
    {
        $log = new Logger(__CLASS__ . __METHOD__);
        $log->debug('map_id:' . $id);

        $map = Tour::find($id);
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
            '*.order' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            if ($validator->fails()) {
                return response()->json([
                    'type' => $validator->errors()->keys()[0],
                    'message' => $validator->errors()->first(),
                ], 401);
            }
        }
        dd($request->all());

        $map->places()->update(['order' => null]);

        DB::transaction(function () use ($map, $request, $log) {
            foreach ($request->json() as $row) {
                $id = $row['id'];
                $loc = $map->places()->find($id);
                $loc->name = $row['name'];
                $loc->description = $row['description'];
                $loc->lat = $row['lat'];
                $loc->lon = $row['lon'];
                $loc->order = $row['order'];
                $loc->save();
            }
        });
        return response()->json($map->places);
    }

    public function updateArrayPlaces($places, $tour_id)
    {
        $log = new Logger(__CLASS__ . __METHOD__);
        $tour = Tour::find($tour_id);
        usort($places, function ($a, $b) {
            return ($a['order'] <=> $b['order']);
        });

        DB::beginTransaction();
        try {
            $tour->places()->update(['order' => null]);
            foreach ($places as $place) {

                if ($place['id'] == null || $place['id'] <= 0) { // Create place
                    $newPlace = new Place();
                    $newPlace->name = $place['name'];
                    $newPlace->description = $place['description'];
                    $newPlace->order = $place['order'];
                    $newPlace->lat = $place['lat'];
                    $newPlace->lon = $place['lon'];
                    $newPlace->image = $place['image'];
                    $tour->places()->save($newPlace);

                } else { // Update place
                    $p = Place::findOrFail($place['id']);
                    $p->name = $place['name'];
                    $p->description = $place['description'];
                    $p->order = $place['order'];
                    $p->lat = $place['lat'];
                    $p->lon = $place['lon'];
                    $p->image = $place['image'];
                    $p->save();
                }
            }

            DB::commit();
        } catch (Exception $e) {
            $log->info($e->getMessage());
            DB::rollback();
            return false;
        }

        return true;
    }

}
