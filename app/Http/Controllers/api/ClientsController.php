<?php

namespace App\Http\Controllers\api;

use App\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::all();

        return response()->json($clients);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client = $request->all();
        $client['created_at'] = Carbon::now();
        $client['updated_at'] = Carbon::now();
        $result = Client::insert($client);
        return response()->json(['result' => $result]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        return response()->json($client);
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
        $client = $request->all();
        $result = Client::find($id)->update($client);
        return response()->json(['result' => $result, 'status' => 'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Client::find($id)->delete();
        return response()->json(['result' => $result]);
    }


    public function deleteAll(Request $request)
    {
        $ids = array_column($request->all(), 'id');
        $result = Client::whereIn('id', $ids)->delete();
        return response()->json(['result'=>$result]);
    }
}
