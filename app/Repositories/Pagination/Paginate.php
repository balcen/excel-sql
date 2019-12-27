<?php

namespace App\Repositories\Pagination;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Paginate
{
    public static function apply(Builder $builder, Request $request)
    {
        return $builder->paginate($request->itemsPerPage);
    }
}
