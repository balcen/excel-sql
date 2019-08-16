<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\RegisterFormRequest;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
}
