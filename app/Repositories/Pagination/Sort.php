<?php

namespace App\Repositories\Pagination;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class Sort
{
    public static function apply(Builder $builder, Request $request)
    {
        $sort = static::applyDecorations($request);
        return $builder->orderBy($sort['sortBy'], $sort['sortDesc']);
    }

    private static function applyDecorations(Request $request)
    {
        return [
            'sortBy' => $request->query('sortBy')[0],
            'sortDesc' => $request->query('sortDesc')[0] === "true" ? 'desc' : 'asc'
        ];
    }
}
