<?php

namespace App\Http\Controllers;

use App\Location;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function getAll()
    {
        return response()->json(Location::all());
    }

    public function get($id)
    {

        return response()->json(Location::find($id));
    }

    public function create(Request $request)
    {
        try {

            $location = Location::create($request->all());
            return response()->json($location, 201);

        } catch (QueryException $e) {
            return response()->json(['error' => 1, 'message' => __FUNCTION__ . "() in " . __FILE__ . " at " . __LINE__]);
        }
    }

    public function update($id, Request $request)
    {
        $location = Location::findOrFail($id);
        $location->update($request->all());

        return response()->json($location, 200);
    }

    public function delete($id)
    {
        Location::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}
