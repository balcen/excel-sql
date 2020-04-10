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
        if (!is_null($request->query('sortBy'))) Sort::apply($builder, $request);
        if ($request->user()->permission < 4) {
            $builder->where('author', $request->user()->id)
                ->orWhere('author', null);
        }
        return Paginate::apply($builder, $request);
    }
}
