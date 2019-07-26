<?php

namespace App\Http\Controllers;

use App\Place;
use App\Play;
use App\User;
use Illuminate\Http\Request;
use Monolog\Logger;
use Validator;

class PlayController extends Controller
{

    public function getPlay(Request $request, $tour_id, $user_id)
    {
        $log = new Logger(__METHOD__);

        $validator = Validator::make(['tour_id' => $tour_id, 'user_id' => $user_id], [
            'user_id' => 'required|exists:users,id',
            'tour_id' => 'required|exists:tours,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['type' => $validator->errors()->keys()[0], 'message' => $validator->errors()->first()], 404);
        }

        if ($user_id != $request->auth->id) {
            return response()->json(['type' => 'authorization', 'message' => 'You are not allowed'], 403);
        }

        $log->debug('user_id=' . $user_id . ',tour_id=' . $tour_id);
        $play = Play::where('user_id', $user_id)->where('tour_id', $tour_id)->first();
        if ($play == null) {
            return response()->json(['type' => 'exist', 'message' => 'The play does not exist'], 404);
        }
        return $this->getPlayById($play->id);
    }

    public function getPlayById($play_id)
    {
        $log = new Logger(__METHOD__);
        $log->debug('play_id=' . $play_id);
        $play = Play::with(['user', 'places', 'tour', 'tour.places'])->find($play_id);
        return response()->json($play);

    }

    public function createPlay(Request $request, $tour_id, $user_id)
    {
        $log = new Logger(__METHOD__);

        $log->debug('tour_id=' . $tour_id . 'user_id=' . $user_id);

        $play = Play::where('user_id', $user_id)
            ->where('tour_id', $tour_id)
            ->first();
        if ($play != null) {
            return response()->json(['type' => 'exist', 'message' => 'The play already exist'], 404);
        }
        $user = User::find($user_id);
        $user->tours()->attach($tour_id);
        $id = Play::where('user_id', $user_id)->where('tour_id', $tour_id)->first()->id;
        return $this->getPlayById($id);
    }

    public function completePlace(Request $request, $play_id, $place_id)
    {
        $log = new Logger(__METHOD__);
        $log->debug('play_id=' . $play_id . ',place_id=' . $place_id);

        $validator = Validator::make(['play_id' => $play_id, 'place_id' => $place_id], [
            'play_id' => 'required|exists:plays,id',
            'place_id' => 'required|exists:places,id',
        ]);
        if ($validator->fails()) {
            return response()->json(['type' => $validator->errors()->keys()[0], 'message' => $validator->errors()->first()], 404);
        }

        $play = Play::find($play_id);
        $place = Place::find($place_id);

        if ($play->tour->id == $place->tour->id) {
            $completedPlaces = count($play->places);
            if ($completedPlaces != $place->order) {
                return response()->json(['type' => 'place', 'message' => 'You can not complete that place yet'], 400);
            }
            $play->places()->attach($place->id);
        } else {
            return response()->json(['type' => 'place', 'message' => 'That place doesn\'t belong to the tour.'], 400);
        }

        return $this->getPlayById($play_id);
    }
}
