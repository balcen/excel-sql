<?php

namespace App\Repositories\Crud;

use App\Repositories\Pagination\Sort;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Pagination\Paginate;


class Index
{
    public static function apply(Model $model, Request $request)
    {
        $builder = $model->newQuery();
        if ($request->sortBy) Sort::apply($builder, $request);
        return Paginate::apply($builder, $request);
    }
}
