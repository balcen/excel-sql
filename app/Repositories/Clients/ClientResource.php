<?php

namespace App\Repositories\Clients;

use App\Entities\Client;
use App\Repositories\Crud;
use Illuminate\Http\Request;

class ClientResource
{
    public static function index(Request $request)
    {
        return Crud\Index::apply(new Client, $request);
    }

    public static function store(Request $request)
    {
        return Crud\Store::apply(new CLient, $request);
    }

    public static function update(Request $request, $id)
    {
        return Crud\Update::apply(new Client, $request, $id);
    }

    public static function destroy($id)
    {
        return Crud\Destroy::apply(new Client, $id);
    }

    public static function batchDelete(Request $request)
    {
        return Crud\BatchDelete::apply(new Client, $request);
    }

    public static function upload(Request $request)
    {
        return Crud\FileUpload::apply(new Client, $request);
    }
}
