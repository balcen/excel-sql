<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\FIle\Type;

class UploadController extends Controller
{
    public function import (Request $request) {
            return response()
                ->json(Type::apply($request));
    }
}
