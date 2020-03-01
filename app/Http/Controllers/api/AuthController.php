<?php

namespace App\Http\Controllers\API;

use App\Entities\User;
use Illuminate\Http\Request;
use App\Repositories\Auth\AuthUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Controllers\Traits\ProxyHelpers;

class AuthController extends Controller
{
    use ProxyHelpers;

    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(RegisterFormRequest $request)
    {
//        try {
//            $user = new User([
//                'name' => $request->input('name'),
//                'email' => $request->input('email'),
//                'password' => bcrypt($request->input('password')),
//            ]);
//            $user->save();
//        } catch (\Exception $exception){
//            return response([
//                'status' => $exception
//            ],500);
//        }
//        return response([
//            'status' => 'success',
//            'data' => $user
//        ], 200);
        $response = new AuthUser($request);
        dd($response);
        return response()->json(
            $response->message['message'],
            $response->message['status']
        );
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
        try {
          return response()->json($this->authenticate());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
//        auth()->logout();
//        auth('api')->logout();
        $request->user()->token()->revoke();
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
