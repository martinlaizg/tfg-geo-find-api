<?php

namespace App\Http\Controllers;

use App\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Monolog\Logger;

class LoginController extends Controller
{

    public function login(Request $request)
    {
        try {
            $log = new Logger("LoginController");
            $email = $request->input('email');
            $password = $request->input('password');

            $encryptedPassword = Hash::make($password);
            $log->info("Login: (email='" . $email . "', password='" . $password . ", encryptedPassword='" . $encryptedPassword . "')");

            $user = User::where([
                ['email', $email],
                ['password', $encryptedPassword],
            ])->firstOrFail();
            $log->info("User: " . $user);
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
