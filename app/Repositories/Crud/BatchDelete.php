<?php

namespace App\Repositories\Crud;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class BatchDelete
{
    public static function apply(Model $model, Request $request)
    {
        return $model
            ->newQuery()
            ->whereIn('id', static::stringToArray($request->ids))
            ->delete();
    }

    private static function stringToArray($string)
    {
        return explode(',', $string);
    }
}
