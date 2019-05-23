<?php

namespace App\Http\Controllers;

use App\Social;
use App\Ticket;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Monolog\Logger;
use Validator;
use \Google_Client;

class UserController extends Controller
{

    public function registry(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $provider = $request->input('provider');
    }

    public function loginProvider($provider, Request $request)
    {
        $log = new Logger(__METHOD__);
        if ($provider == "own") {
            $log->debug('Own login, redirected');
            return $this->login($request);
        }
        $token = $request->input('token');
        $log->debug('token=' . $token);

        $sub = "";
        $email = "";

        // Google login
        if ($provider == 'google') {
            $log->debug('login with google');
            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $payload = $client->verifyIdToken($token);
            if ($payload) {
                $sub = $payload['sub'];
                $email = $payload['email'];
            } else {
                $log->debug("Wrong " . $provider . " login");
                return response()->json([
                    'type' => 'provider_login',
                    'message' => 'Wrong login']
                    , 400);
            }
        } else {
            return response()->json([
                'type' => 'provider',
                'message' => 'Wrong provider']
                , 400);
        }

        $user = Social::where('sub', $sub)->where('provider', $provider)->first()->user;

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $log = new Logger(__METHOD__);

        try {
            $email = $request->input('email');
            $password = $request->input('password');

            $log->info("Login email=" . $email);
            $log->debug("Login password=" . $password);

            $user = User::where('email', $email)->first();
            if ($user == null) {
                return response()->json([
                    'type' => 'email',
                    'message' => 'Invalid email']
                    , 404);
            }
            $user = User::where([
                ['email', $email],
                ['password', $password],
            ])->firstOrFail();
            return response()->json($user);
        } catch (Exception $e) {
            $log->debug("Wrong password");
            return response()->json([
                'type' => 'password',
                'message' => 'Wrong password']
                , 404);
        }
    }

    public function get($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return response()->json([
                'type' => 'id',
                'message' => 'El id no existe',
            ], 404);
        }
        return response()->json($user, 200);
    }

    public function create(Request $request)
    {
        $log = new Logger('UserController - createUser');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'username' => 'required|unique:users',
            'name' => 'required',
            'password' => 'required',
            'user_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first(),
            ], 401);
        }

        $u = new User;
        try {
            $u->email = $request->input('email');
            $u->name = $request->input('name');
            $u->username = $request->input('username');
            $u->password = $request->input('password');
            $u->user_type = $request->input('user_type');
            $log->debug($u);
            $u->save();
        } catch (QueryException $e) {
            $log->debug(__FUNCTION__ . "() in " . __FILE__ . " at " . __LINE__ . " // " . $e->getMessage());
            return response()->json(['error' => 1, 'message' => 'QueryException'], 500);
        }
        return response()->json($u);
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

    public function postMessage(Request $request)
    {
        $title = $request->input('title');
        $message = $request->input('message');

        $ticket = new Ticket();
        $ticket->title = $title;
        $ticket->message = $message;
        $ticket->save();

        return response()->json();
    }
}
