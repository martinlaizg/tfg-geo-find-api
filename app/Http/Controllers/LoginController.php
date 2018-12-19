<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        try {
			$user = User::where('email',$request->email)->firstOrFail();
            return response()->json($user, 201);

        } catch (QueryException $e) {
            return response()->json(['error' => 1, 'message' => __FUNCTION__ . "() in " . __FILE__ . " at " . __LINE__]);
        }
	}
	public function register(Request $request)
    {
        try {
			$user = User::create($request->all());
            return response()->json($user, 201);

        } catch (QueryException $e) {
            return response()->json(['error' => 1, 'message' => __FUNCTION__ . "() in " . __FILE__ . " at " . __LINE__]);
        }
    }

}
