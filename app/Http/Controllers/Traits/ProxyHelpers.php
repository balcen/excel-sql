<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Validation\UnauthorizedException;

trait ProxyHelpers
{
    public function authenticate()
    {
        $http = new Client();

        try {
            $url = request()->root() . '/api/oauth/token';

            $params = array_merge(config('passport.proxy'), [
                'username' => request('email'),
                'password' => request('password')
            ]);

            $respond = $http->post($url, ['form_params' => $params]);
        } catch(RequestException $e) {
            throw new UnauthorizedException('請求失敗，伺服器錯誤');
        }

        if ($respond->getStatusCode() !== 401) {
            return json_decode($respond->getBody()->getContents(), true);
        }

        throw new UnauthorizedException('帳號密碼錯誤');
    }

    public function issueToken()
    {
        $user = request()->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if (request('remember_me')) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
    }
}
