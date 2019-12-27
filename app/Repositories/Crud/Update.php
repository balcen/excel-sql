<?php

namespace App\Repositories\Crud;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class Update
{
    public static function apply(Model $model, Request $request, $id)
    {
        return $model->newQuery()->find($id)->update($request->all());
    }
}
