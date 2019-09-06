<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RegisterFormRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterFormRequest $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function login($request)
    {
        $credential = $request->only('email', 'password');

        // attempt to verify the credential and create token for the user
        if (!$token = JWTAuth::attempt($credential)) {
            return response([
                'status' => 'error',
                'error' => 'invalid.credential',
                'msg' => 'Invalid Credential'
            ], 400);
        }

        //
        return response(['status' => 'success'])
            ->header('Authorization', $token);
    }

    // checkout user info
    public function user()
    {
        $user = User::find(Auth::id());

        return response([
            'status' => 'success',
            'data' => $user
        ]);
    }

    // checkout token valid or not
    public function refresh()
    {
        return response(['status' => 'sueccess']);
    }
}
