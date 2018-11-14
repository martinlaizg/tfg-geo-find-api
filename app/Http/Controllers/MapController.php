<?php

namespace App\Http\Controllers;

use App\Map;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MapController extends Controller
{

    public function getAll()
    {
        return response()->json(Map::all());
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
