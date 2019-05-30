<?php

namespace App\Http\Controllers;

use App\Social;
use App\Ticket;
use App\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Monolog\Logger;
use Validator;
use \Google_Client;

class UserController extends Controller
{

    public function update($user_id, Request $request)
    {
        $log = new Logger(__METHOD__);

        $user = User::find($user_id);
        if ($user == null) {
            return response()->json([
                'type' => 'exist',
                'message' => 'The user do not exist',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'provider' => ['required', Rule::in(['own', 'google'])],
            'secure' => 'required|string',
            'user.email' => 'email',
            'user.name' => 'string',
            'user.username' => 'string',
            'user.secure' => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'type' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first(),
            ], 401);
        }

        $provider = $request->input('provider');
        $secure = $request->input('secure');

        if ($provider == 'own') {
            if ($user->password != $secure) {
                return response()->json(['type' => 'secure', 'message' => 'Wrong secure'], 401);
            }
        } else {
            $payload = null;
            if ($provider == 'google') {
                $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
                $payload = $client->verifyIdToken($secure);
            }

            $sub = null;
            if ($payload != null) {
                $sub = $payload['sub'];
            } else {
                return response()->json(['type' => 'secure', 'message' => 'Wrong secure'], 401);
            }

            if ($payload == null || $sub == null) {
                return response()->json(['type' => 'secure', 'message' => 'Wrong secure'], 401);
            }
            $token_user = Social::where('sub', $sub)->where('provider', $provider)->first()->user;
            if ($token_user->id != $user->id) {
                return response()->json(['type' => 'secure', 'message' => 'Wrong secure'], 401);
            }
        }
        $user->name = $request->input('user.name');
        $user->email = $request->input('user.email');
        $user->username = $request->input('user.username');
        $user->save();

        return response()->json($user, 200);
    }

    public function loginProvider($provider, Request $request)
    {
        $log = new Logger(__METHOD__);
        if ($provider == 'own') {
            $log->debug('Own login, redirected');
            return $this->login($request);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'provider' => ['required', Rule::in(['own', 'google'])],
            'secure' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['type' => $validator->errors()->keys()[0], 'message' => $validator->errors()->first()], 401);
        }

        $secure = $request->input('secure');
        $log->debug('secure=' . $secure);

        $sub = '';
        $email = '';
        $name = null;
        $image = null;

        // Google login
        if ($provider == 'google') {
            $log->debug('login with google');
            $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
            $payload = $client->verifyIdToken($secure);
            if ($payload) {
                $sub = $payload['sub'];
                $email = $payload['email'];
                $name = $payload['name'];
                $image = $payload['picture'];
            } else {
                $log->debug('Wrong ' . $provider . ' login');
                return response()->json(['type' => 'secure', 'message' => 'Invalid token'], 400);
            }
        } else {
            return response()->json(['type' => 'provider', 'message' => 'Wrong provider'], 400);
        }
        if ($sub == null || $provider == null) {
            return response()->json(['type' => 'provider_login', 'message' => 'Error login provider'], 400);
        }

        $social = Social::where('sub', $sub)->where('provider', $provider)->first();
        $user = User::where('email', $email)->first();
        if ($user == null) {
            $user = new User;
            $user->email = $email;
            $user->name = $name;
            $user->image = $image;
            $user->save();
        }
        if ($social == null) {
            $social = new Social;
            $social->sub = $sub;
            $social->provider = $provider;
            $user->socials()->save($social);
        }

        return response()->json($user);
    }

    public function login(Request $request)
    {
        $log = new Logger(__METHOD__);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'secure' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first(),
            ], 401);
        }

        try {

            $email = $request->input('email');
            $secure = $request->input('secure');

            $log->info('Login email=' . $email);
            $log->debug('Login secure=' . $secure);

            $user = User::where('email', $email)->first();
            if ($user == null) {
                return response()->json([
                    'type' => 'email',
                    'message' => 'Invalid email']
                    , 404);
            }
            $user = User::where([
                ['email', $email],
                ['password', $secure],
            ])->firstOrFail();
            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            $log->debug('Wrong secure');
            return response()->json([
                'type' => 'secure',
                'message' => 'Wrong secure']
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
        $log->debug($request);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'secure' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => $validator->errors()->keys()[0],
                'message' => $validator->errors()->first(),
            ], 401);
        }

        $u = new User;
        $u->email = $request->input('email');
        $u->password = $request->input('secure');
        $u->save();
        return response()->json($u);
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
