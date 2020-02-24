<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use Illuminate\Validation\UnauthorizedException;

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
//            if (auth()->check()) {
                $token = $this->getToken();
                $this->remember($token);
//            } else {
//                throw new UnauthorizedException('帳號密碼錯誤');
//            }
            return $token;
        } catch (\Exception $e) {
//            return ['error' => $e->getMessage()];
            throw new UnauthorizedException($e->getMessage());
        }
    }

    public function attempt()
    {
        if (!auth()->attempt(request(['name', 'password']))) {
            throw new UnauthorizedException('Unauthorized');
        }
    }

    public function getToken()
    {
        return [
            'token' =>request()->user()->createToken('Personal Access Token')->accessToken,
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
