<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;

trait ProxyHelpers
{
//    public function authenticate()
//    {
//        $http = new Client();
//
//        try {
//            $url = request()->root() . '/api/oauth/token';
//
//            $params = array_merge(config('passport.proxy'), [
//                'username' => request('email'),
//                'password' => request('password')
//            ]);
//
//            $respond = $http->post($url, ['form_params' => $params]);
//        } catch(RequestException $e) {
//            throw new UnauthorizedException('請求失敗，伺服器錯誤');
//        }
//
//        if ($respond->getStatusCode() !== 401) {
//            return json_decode($respond->getBody()->getContents(), true);
//        }
//
//        throw new UnauthorizedException('帳號密碼錯誤');
//    }

    public function authenticate()
    {
        try {
            $this->attempt();
            $token = $this->getToken();
            $this->remember($token);
            return $token;
        } catch (\Exception $e) {
            return ['error' => $e];
        }
    }

    public function attempt()
    {
        if (\Auth::attempt(request(['name', 'password']))) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    public function getToken()
    {
        return [
            'access_token' =>request()->user()->createToken('Personal Access Token')->accessToken,
            'token_type' => 'Bearer'
            ];
    }

    public function remember(&$token)
    {
        if (request('remember_me')) {
            $token['expires_at'] = Carbon::now()->addDay()->toDateString();
        }
    }
}
