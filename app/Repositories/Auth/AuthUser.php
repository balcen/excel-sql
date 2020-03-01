<?php

namespace App\Repositories\Auth;

use App\Entities\User;
use Illuminate\Foundation\Http\FormRequest;

class AuthUser
{
    protected $name;
    protected $email;
    protected $password;
    public $message;

    public function __construct(FormRequest $request)
    {
        $this->name = $request->input('name');
        $this->email = $request->input('email');
        $this->password = bcrypt($request->input('password'));
        $this->attempt();
    }

    public function attempt()
    {
        try {
            $user = new User([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password
            ]);
            $user->save();
            $token = $user->createToken('Access Token')->accessToken;
            $this->message = [
                'status' => 200,
                'message' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'status' => 'success',
                    'data' => $user
                ]
            ];
        } catch (\Exception $e) {
            $this->message = [
                'status' => 500,
                'message' => ['status' => $e->getMessage()]
            ];
        }
    }
}
