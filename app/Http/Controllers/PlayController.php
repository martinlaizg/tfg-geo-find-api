<?php

namespace App\Http\Controllers;

use App\Place;
use App\Play;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Monolog\Logger;
use Validator;

class PlayController extends Controller
{

    public function getPlay(Request $request)
    {
        $log = new Logger(__METHOD__);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tour_id' => 'required|exists:tours,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first(),
            ], 404);
        }

        $user_id = $request->input('user_id');
        $tour_id = $request->input('tour_id');

        $log->debug('user_id=' . $user_id . ',tour_id=' . $tour_id);
        $play = Play::where('user_id', $user_id)->where('tour_id', $tour_id)->first();
        if ($play == null) {
            return response()->json([
                'type' => 'exist',
                'message' => 'The play does not exist',
            ], 404);
        }
        return $this->getPlayById($play->id);
    }

    private function getPlayById($play_id)
    {
        $log = new Logger(__METHOD__);
        $log->debug('play_id=' . $play_id);
        $play = Play::with(['user', 'places', 'tour', 'tour.places'])->find($play_id);
        return response()->json($play);

    }

    public function createPlay(Request $request)
    {
        $log = new Logger(__METHOD__);
        $tour_id = $request->input('tour_id');
        $user_id = $request->input('user_id');

        $log->debug('tour_id=' . $tour_id . 'user_id=' . $user_id);

        $play = Play::where('user_id', $user_id)
            ->where('tour_id', $tour_id)
            ->first();
        if ($play != null) {
            return response()->json([
                'type' => 'exist',
                'message' => 'The play already exist',
            ], 404);
        }
        $user = User::find($user_id);
        $user->tours()->attach($tour_id);
        $id = Play::where('user_id', $user_id)->where('tour_id', $tour_id)->first()->id;
        return $this->getPlayById($id);
    }

    public function completePlace($play_id, Request $request)
    {
        $log = new Logger(__METHOD__);

        $place_id = $request->input('place_id');
        $log->debug('play_id=' . $play_id . ',place_id=' . $place_id);

        $play = Play::find($play_id);
        if ($play == null) {
            return response()->json([
                'type' => 'exist',
                'message' => 'The place do not exist',
            ], 404);
        }

        $place = Place::find($place_id);
        if ($place == null) {
            return response()->json([
                'type' => 'exist',
                'message' => 'The place do not exist',
            ], 404);
        }

        if ($play->tour->id == $place->tour->id) {
            try {
                $play->places()->attach($place->id);
            } catch (QueryException $e) {
                return response()->json([
                    'type' => 'exist',
                    'message' => 'This play already complete the place.',
                ], 400);
            }
        } else {
            return response()->json([
                'type' => 'exist',
                'message' => 'That place doesn\'t belong to the tour.',
            ], 400);
        }

        return $this->getPlayById($play_id);
    }
}
