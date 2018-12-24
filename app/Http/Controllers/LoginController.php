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
            $user = User::where('email', $email)->firstOrFail();
            // $cryptedPassword = bcrypt($password);
            $cryptedPassword = $password;
            if ($user->password != $cryptedPassword) {
                throw new Exception("Incorrect user or password");
            }
            return response()->json($user);
        } catch (Exception $e) {
            return response()->json(['error' => 1, 'message' => 'Email or password incorrecto']);
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
