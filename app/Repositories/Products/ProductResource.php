<?php

namespace App\Repositories\Products;

use App\Entities\Product;
use App\Repositories\Crud;
use Illuminate\Http\Request;

class ProductResource
{
    public static function index(Request $request)
    {
        return Crud\Index::apply(new Product, $request);
    }

    public static function store(Request $request)
    {
        return Crud\Store::apply(new Product, $request);
    }

    public static function update(Request $request, $id)
    {
        return Crud\Update::apply(new Product, $request, $id);
    }

    public static function destroy($id)
    {
        return Crud\Destroy::apply(new Product, $id);
    }

    public static function batchDelete(Request $request)
    {
        return Crud\BatchDelete::apply(new Product, $request);
    }
}
