<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExcelFileUpload;
use App\Repositories\Clients\ClientSearch;
use App\Repositories\Clients\ClientResource;

class ClientsController extends Controller
{
    use ExcelFileUpload;

    public function __construct()
    {
        $this->middleware('cors');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()
            ->json(ClientResource::index($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()
            ->json(ClientResource::store($request));
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return response()
            ->json(ClientResource::update($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()
            ->json(ClientResource::destroy($id));
    }


    public function deleteAll(Request $request)
    {
        return response()
            ->json(ClientResource::batchDelete($request));
    }

    public function upload(Request $request)
    {
        return response()
            ->json(['id' => ClientResource::upload($request)]);
    }

    public function searchAll(Request $request)
    {
        return response()
            ->json(ClientSearch::apply($request));
    }
}
