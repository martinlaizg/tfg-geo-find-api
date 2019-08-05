<?php

namespace App\Http\Controllers;

use App\Social;
use App\Ticket;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Monolog\Logger;
use UnexpectedValueException;
use Validator;
use \Google_Client;

class UserController extends Controller
{

    public function update($user_id, Request $request)
    {
        $log = new Logger(__METHOD__);
        $log->debug('user_id=' . $user_id);

        $user = User::find($user_id);
        if ($user == null) {
            $log->info('The user do not exist');
            return response()->json(['type' => 'exist', 'message' => 'The user do not exist'], 404);
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
            $array = explode('.', $validator->errors()->keys()[0]);
            return response()->json(['type' => end($array), 'message' => $validator->errors()->first()], 400);
        }

        $provider = $request->input('provider');
        $secure = $request->input('secure');

        if ($provider == 'own') {
            // The secure is the password
            if ($user->password != $secure) {
                return response()->json(['type' => 'secure', 'message' => 'Wrong secure'], 400);
            }
        } else {
            // The secure is the sub of the provider
            $social = Social::where('sub', $secure)->where('provider', $provider)->first();
            if ($social == null) {
                $log->info('Invalid sub');
                return response()->json(['type' => 'secure', 'message' => 'Wrong secure'], 400);
            }
            $token_user = $social->user;
            if ($token_user->id != $user->id) {
                $log->info('Required user no match the sub');
                return response()->json(['type' => 'secure', 'message' => 'Wrong secure'], 400);
            }
        }
        $user->name = $request->input('user.name');

        // Validate duplicated email if changes
        $newEmail = $request->input('user.email');
        if ($newEmail != $user->email) {
            if (User::where('email', $newEmail)->first() != null) {
                $log->info('Email already in use');
                return response()->json(['type' => 'email', 'message' => 'Email already in use'], 400);
            }
            $user->email = $newEmail;
        }

        // Validate duplicated username if changes
        $newUsername = $request->input('user.username');
        if ($newUsername != null && $newUsername != $user->username) {
            if (User::where('username', $newUsername)->first() != null) {
                $log->info('Username already in use');
                return response()->json(['type' => 'username', 'message' => 'Username already in use'], 400);
            }
            $user->username = $newUsername;
            if ($user->user_type == 'user') {
                $user->user_type = 'creator';
            }
        }

        $password = $request->input('user.secure');
        if ($password != null) {
            $user->password = $password;
        }
        $user->save();
        return response()->json($user, 200);
    }

    public function login(Request $request)
    {
        $log = new Logger(__METHOD__);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'provider' => ['required', Rule::in(['own', 'google'])],
            'secure' => 'required|string',
        ]);
        if ($validator->fails()) {
            $array = explode('.', $validator->errors()->keys()[0]);
            return response()->json(['type' => end($array), 'message' => $validator->errors()->first()], 400);
        }

        $email = $request->input('email');
        $provider = $request->input('provider');
        $secure = $request->input('secure');

        $sub = '';
        $name = null;
        $image = null;

        $log->debug('login with ' . $provider);
        // Google login
        if ($provider == 'google') {
            try {
                $client = new Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
                $payload = $client->verifyIdToken($secure);
            } catch (UnexpectedValueException $e) {
                $log->debug('Wrong provider token');
                return response()->json(['type' => 'secure', 'message' => 'Invalid provider token'], 400);
            }
            if ($payload) {
                $sub = $payload['sub'];
                $token_email = $payload['email'];
                $name = $payload['name'];
                $image = $payload['picture'];
            }
            if (!$payload || $email != $token_email) {
                $log->debug('Wrong login');
                return response()->json(['type' => 'secure', 'message' => 'Invalid token'], 400);
            }
        }

        $user = User::where('email', $email)->first();
        if ($user == null) {
            if ($provider == 'own') {
                $log->debug('Wrong email');
                return response()->json(['type' => 'email', 'message' => 'Invalid email'], 400);
            }
            $user = new User;
            $user->email = $email;
            $user->name = $name;
            $user->image = $image;
            $user->user_type = 'user';
            $user->save();
        }
        if ($provider == 'own') {
            if ($user->password != $secure) {
                $log->debug('Wrong password');
                return response()->json(['type' => 'password', 'message' => 'Invalid password'], 400);
            }
        }

        $social = $user->socials()->where('provider', $provider)->first();
        if ($social == null) {
            $social = new Social;
            $social->sub = $sub;
            $social->provider = $provider;
            $user->socials()->save($social);
        } else if ($social->sub != $sub) {
            $log->debug('Wrong sub');
            return response()->json(['type' => 'token', 'message' => 'Invalid token'], 400);
        }
        return response($user)->header('Authorization', $this->jwt($user));
    }

    public function create(Request $request)
    {
        $log = new Logger(__METHOD__);
        $log->debug($request);
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'secure' => 'required|string',
        ]);

        if ($validator->fails()) {
            $array = explode('.', $validator->errors()->keys()[0]);
            return response()->json(['type' => end($array), 'message' => $validator->errors()->first()], 400);
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
        $ticket->user_id = $request->auth->id;
        $ticket->title = $title;
        $ticket->message = $message;
        $ticket->save();

        return response()->json();
    }

    protected function jwt(User $user)
    {
        $payload = [
            'iss' => "lumen-jwt", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued.
            'exp' => time() + (60 * 60 * 24 * 7), // Expiration date 60s * 60min * 24h * 7d
        ];

        // As you can see we are passing `JWT_SECRET` as the second parameter that will
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    }
}
