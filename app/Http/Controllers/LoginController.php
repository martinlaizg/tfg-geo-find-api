<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Monolog\Logger;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        try {
			$log = new Logger("LoginController");

            $email = $request->input('email');
            $password = $request->input('password');

			$log->debug("Login: (email='" . $email . "', password='" . $password . ")");
            $user = User::where([
                ['email', $email],
                ['password', $password],
                // ['password', $encryptedPassword],
            ])->firstOrFail();
            $log->debug("User: " . $user);

			return response()->json($user);
        } catch (Exception $e) {
            $log->debug("Invalid user or password");
            return response()->json(['error' => 1, 'message' => 'Invalid user or password'], 404);
        }
    }

    public function register(Request $request)
    {
        try {
            $user = User::create($request->all());
            return response()->json($user, 201);

        } catch (QueryException $e) {
            return response()->json(['error' => 1, 'message' => "Invalid user or password"], 404);
        }
    }

}
