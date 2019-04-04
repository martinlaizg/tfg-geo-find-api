<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Monolog\Logger;
use Validator;

class UserController extends Controller
{

    public function getAll()
    {
        return response()->json(User::get());
    }

    public function get($id)
    {
        return response()->json(User::find($id));
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
            return response()->json(['error' => 1, 'message' => 'QueryException']);
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
}
