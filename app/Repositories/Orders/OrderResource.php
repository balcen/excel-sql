<?php

namespace App\Repositories\Orders;

use App\Entities\Order;
use App\Repositories\Crud;
use Illuminate\Http\Request;

class OrderResource
{
    public static function index(Request $request)
    {
        return Crud\Index::apply(new Order, $request);
    }

    public static function store(Request $request)
    {
        return Crud\Store::apply(new Order, $request);
    }

    public static function update(Request $request, $id)
    {
        return Crud\Update::apply(new Order, $request, $id);
    }

    public static function destroy($id)
    {
        return Crud\Destroy::apply(new Order, $id);
    }

    public static function batchDelete(Request $request)
    {
        return Crud\BatchDelete::apply(new Order, $request);
    }

    public static function upload(Request $request)
    {
        return (new Crud\FileUpload)->apply(new Order, $request);
    }
}
