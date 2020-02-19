<?php

namespace App\Http\Controllers\API;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Controllers\Traits\ProxyHelpers;

class AuthController extends Controller
{
    use ProxyHelpers;

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'register']]);
    }

    public function register(RegisterFormRequest $request)
    {
        try {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->save();
        } catch (\Exception $exception){
            return response([
                'status' => $exception
            ],500);
        }
        return response([
            'status' => 'success',
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
//        $credentials = request(['name', 'password']);
//        try {
//            //  attempt to verify the credential and create token for the user
//            if (! Auth::attempt($credentials)) {
//                return response()->json(['error'=>'Unauthorized'], 401);
//            }
//            if (! auth('web')->attempt($credentials)) {
//                return  response()->json(['error' => 'Unauthorized'], 401);
//            }
//            $user = $request->user();
//            $tokenResult = $user->createToken('Personal Access Token');
//            $token = $tokenResult->token;
//
//            if (request('remember_me')) {
//               $expires_at = Carbon::now()->addDay();
//            }
//
//            return response()->json([
//                'access_token' => $tokenResult->accessToken,
//                'token_type' => 'Bearer',
//                'expires_at' => Carbon::parse($expires_at)->toDateString()
//            ]);
//
//        } catch (\Exception $e) {
//            return response()->json(['error' => 'could_not_create_token'],500);
//        }
//        return $this->respondWithToken($token);
//        return response()->json(['success'=>'success'])->cookie('token', $token, 30, null, null, false, false);
//        return response()->json(['token' => $respond]);

        return response()->json($this->authenticate());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // return $this->respondWithToken(auth()->refresh());
        return response()->json(['success' => '更新成功']);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ],200)->header('Authorization', $token);
    }
}
