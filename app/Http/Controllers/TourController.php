<?php

namespace App\Http\Controllers;

use App\Place;
use App\Tour;
use App\User;
use Exception;
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
        $log = new Logger(__METHOD__);

        $tour = Tour::find($id);
        if ($tour == null) {
            return response()->json(['type' => 'id', 'message' => 'The tour no exist'], 404);
        }

        // validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'description' => 'required|string',
            'min_level' => ['required', Rule::in(['map', 'compass', 'therm'])],
            'image' => 'string',
            'places' => 'required|array|min:1',
            'places.*.name' => 'required|string',
            'places.*.description' => 'required|string',
            'places.*.question' => 'string|distinct',
            'places.*.answer' => 'string',
            'places.*.answer2' => 'string',
            'places.*.answer3' => 'string',
            'places.*.lat' => 'required|numeric',
            'places.*.lon' => 'required|numeric',
            'places.*.order' => 'required|numeric|distinct',
        ]);
        if ($validator->fails()) {
            if ($validator->fails()) {
                $array = explode('.', $validator->errors()->keys()[0]);
                return response()->json(['type' => end($array), 'message' => $validator->errors()->first()], 400);
            }
        }

        $places = $request->input('places');
        // First update places
        $response = $this->updateArrayPlaces($places, $tour);
        if ($response != null) {
            return $response;
        }
        // Then update the tour
        $tour->name = $request->input('name');
        $tour->description = $request->input('description');
        $tour->image = $request->input('image');
        $tour->min_level = $request->input('min_level');
        $tour->save();
        return response()->json(Tour::query()->with(['places', 'creator'])->find($tour->id), 200);
    }

    private function getCoordWithMargin($userCoord)
    {
        $margin = 10;
        return [$userCoord - $margin, $userCoord + $margin];
    }

    public function getAll(Request $request)
    {
        try {
            $log = new Logger(__METHOD__);
            $coords = $request->input('coords');
            $tours = Tour::query();
            if ($coords != '') {
                $log->debug('coords=' . $coords);
                $userLat = strtok($coords, ',');
                $userLon = strtok(',');
                $lat = $this->getCoordWithMargin($userLat);
                $lon = $this->getCoordWithMargin($userLon);
                $log->debug('lat=' . $userLat . '; range=(' . implode(',', $lat) . ')');
                $log->debug('lon=' . $userLon . '; range=(' . implode(',', $lon) . ')');
                $tours = $tours->whereHas('places', function ($query) use ($lat, $lon) {
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
                    $tours = $tours->where('creator_id', $creator_id);
                }
            }
            $search = $request->input('q');
            if ($search != '') {
                $log->debug('search=' . $search);
                $tours = $tours
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
            return response()->json($tours->with(['creator', 'places' => function ($query) {
                $query->orderBy('order');
            }])->get(), 200);

        } catch (QueryException $e) {
            return response()->json(['error' => 1, 'message' => $e->getMessage()]);
        }
    }

    public function getSingleTour($id)
    {
        $log = new Logger(__METHOD__);
        $log->debug('tour_id=' . $id);

        $tour = Tour::where('id', $id)->with(['creator', 'places'])->first();
        if ($tour == null) {
            return response()->json(['type' => 'id','message' => 'The tour no exist',], 404);
        }
        return response()->json($tour);
    }

    public function create(Request $request)
    {
        $log = new Logger(__METHOD__);
        $log->debug($request);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'image' => 'string',
            'min_level' => 'required',
            'creator_id' => 'required|exists:users,id',
            'places' => 'required|array|min:1',
            'places.*.name' => 'required|string',
            'places.*.description' => 'required|string',
            'places.*.image' => 'string',
            'places.*.order' => 'required|numeric|distinct|min:0',
            'places.*.lat' => 'required|numeric|min:-90|max:90',
            'places.*.lon' => 'required|numeric|min:-180|max:180',
            'places.*.question' => 'string|distinct',
            'places.*.answer' => 'string',
            'places.*.answer2' => 'string',
            'places.*.answer3' => 'string',
        ]);
        if ($validator->fails()) {
            if ($validator->fails()) {
                $array = explode('.', $validator->errors()->keys()[0]);
                return response()->json(['type' => end($array), 'message' => $validator->errors()->first()], 400);
            }
        }

		// Creator always exist (see validator)
		$creator = User::find($request->input('creator_id'));
		if($creator->user_type == 'user' ){
			$log->info('The creator has not permissions');
			return response()->json(['type' => 'permissions', 'message' => 'The creator has not permissions'], 400);
		}

        // Create the tour
        DB::beginTransaction();
        $tour = new Tour;
        $tour->name = $request->input('name');
        $tour->description = $request->input('description');
        $tour->min_level = $request->input('min_level');
        $tour->image = $request->input('image');
        $creator->createdTours()->save($tour);

        // Sort places to check the order
        $places = $request->input('places');
        usort($places, function ($a, $b) {
            return ($a['order'] <=> $b['order']);
        });
        $last = end($places);
        if ((count($places) - 1) != $last['order']) {
            return response()->json(['type' => 'order', 'message' => 'Wrong place order'], 400);
        }
        // Create places
        foreach ($places as $entry) {
            $place = new Place;
            $place->name = $entry['name'];
            $place->description = $entry['description'];
            if (isset($entry['image'])) {
                $place->image = $entry['image'];
            }
            $place->order = (int) $entry['order'];
            $place->lat = (double) $entry['lat'];
            $place->lon = (double) $entry['lon'];

            if (isset($entry['question'])) {
                $place->question = $entry['question'];
                // If question is setted, check answers
                if (!isset($entry['answer']) || !isset($entry['answer2']) || !isset($entry['answer3'])) {
                    DB::rollback();
                    return response()->json(['type' => 'answers', 'message' => 'The three answers are required'], 400);
                }
                $place->answer = strval($entry['answer']);
                $place->answer2 = strval($entry['answer2']);
                $place->answer3 = strval($entry['answer3']);
                // Check repeated answers
                if ($place->answer == $place->answer2 || $place->answer == $place->answer3 || $place->answer2 == $place->answer3) {
                    DB::rollback();
                    return response()->json(['type' => 'answers', 'message' => 'The three answers should be different'], 400);
                }
            }
            // Save places
            $tour->places()->save($place);
        }
        DB::commit();
        $tour->load(['creator', 'places']);
        return response()->json($tour);
    }

    private function updateArrayPlaces($newPlaces, $tour)
    {
        $log = new Logger(__METHOD__);
        // Sort places to check the order
        usort($newPlaces, function ($a, $b) {
            return -($a['order'] <=> $b['order']);
        });

        $lastNewPlaces = reset($newPlaces)['order'];
        if ((count($newPlaces) - 1) != $lastNewPlaces) {
            return response()->json(['type' => 'order', 'message' => 'Wrong place order'], 400);
        }

        DB::beginTransaction();
        foreach ($newPlaces as $place) {

            // Increase orders
            $tour->places()->where('order', '<=', $place['order'])->decrement('order');

            // Create o get the place (if exist)
            $newPlace = new Place();
            if (isset($place['id']) && $place['id'] > 0) { // Create place
                $newPlace = $tour->places()->find($place['id']);
                if ($newPlace == null) {
                    DB::rollback();
                    return response()->json(['type' => 'exist', 'message' => 'Place ' . $place['id'] . ' do not exist'], 400);
                }
            }

            // Set the new data
            $newPlace->name = $place['name'];
            $newPlace->description = $place['description'];
            $newPlace->order = $place['order'];
            $newPlace->lat = $place['lat'];
            $newPlace->lon = $place['lon'];
            if (isset($place['image'])) {
                $newPlace->image = $place['image'];
            }
            if (isset($place['question'])) {
                if (!isset($place['answer']) || !isset($place['answer2']) || !isset($place['answer3'])) {
                    DB::rollback();
                    return response()->json(['type' => 'answers', 'message' => 'The three answers are required'], 400);
                }
                $newPlace->question = $place['question'];
                $newPlace->answer = $place['answer'];
                $newPlace->answer2 = $place['answer2'];
                $newPlace->answer3 = $place['answer3'];
            }

            // The place already exist
            if ($newPlace->id != null) {
                $newPlace->save();
            } else {
                $tour->places()->save($newPlace);
            }
        }

        $tour->places()->where('order', '<', 0)->delete();
        $tour->places()->where('order', '>', count($newPlaces) - 1)->delete();
        DB::commit();
    }

}
