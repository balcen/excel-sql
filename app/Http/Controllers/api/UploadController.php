<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\FileService;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    protected $service;

    public function __construct(FileService $service)
    {
        $this->service = $service;
    }

    public function import (Request $request)
    {
        return response()->json($this->service->import($request));
    }
}
