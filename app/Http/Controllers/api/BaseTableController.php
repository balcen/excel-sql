<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use app\Services\Table\TableService as Service;

class BaseTableController extends Controller
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->middleware('auth:api');
        $this->service = $service;
    }

    public function index(Request $request)
    {
        return response()->json($this->service->index($request));
    }

    public function store(Request $request)
    {
        return response()->json($this->service->store($request));
    }

    public function show(Request $request, $id)
    {
    }

    public function update(Request $request, $id)
    {
        return response()->json($this->service->update($request, $id));
    }

    public function destroy($id)
    {
        return response()->json($this->service->destroy($id));
    }


    public function batchDelete(Request $request)
    {
        return response()->json($this->service->batchDelete($request));
    }

    public function upload(Request $request)
    {
        return response()->json($this->service->upload($request));
    }

    public function search(Request $request)
    {
        return response()->json($this->service->search($request));
    }
}
