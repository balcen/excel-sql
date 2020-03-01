<?php

namespace App\Repositories\Crud;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Store
{
    public static function apply(Model $model, Request $request)
    {
        $arr = $request->all();
        $arr['author'] = $request->user()->id;
        return $model->newQuery()->create($arr);
    }
}
