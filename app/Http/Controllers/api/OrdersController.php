<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Orders\OrderSearch;
use App\Http\Controllers\ExcelFileUpload;
use App\Repositories\Orders\OrderResource;

class OrdersController extends Controller
{
    use ExcelFileUpload;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()
            ->json(OrderResource::index($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return response()
            ->json(OrderResource::store($request));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()
            ->json(OrderResource::update($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return response()
            ->json(OrderResource::destroy($id));
    }

    public function deleteAll(Request $request)
    {
        return response()
            ->json(OrderResource::batchDelete($request));
    }

    public function upload(Request $request)
    {
        return response()
            ->json(['id' => OrderResource::upload($request)]);
    }

    public function searchAll(Request $request)
    {
        return response()
            ->json(OrderSearch::apply($request));
    }
}
