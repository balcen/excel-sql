<?php

namespace App\Repositories\Crud;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Store
{
    public static function apply(Model $model, Request $request)
    {
        return $model->newQuery()->create($request->all());
    }
}
