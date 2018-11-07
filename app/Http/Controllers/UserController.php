<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Log;

class UserController extends Controller {

	public function getAll()
    {
        return response()->json(User::all());
    }

    public function get($id)
    {
		
        return response()->json(User::find($id));
    }

    public function create(Request $request)
    {
		try{
			
			$user = User::create($request->all());
			return response()->json($user, 201);

		} catch(QueryException $e){
			return response()->json(['error' => 1, 'message'=>__FUNCTION__."() in ".__FILE__." at ".__LINE__]);
		}
	}

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->all());

        return response()->json($user, 200);
    }

    public function delete($id)
    {
        User::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}