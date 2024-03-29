<?php

namespace App\Repositories\Products;

use App\Entities\Product;
use Illuminate\Http\Request;
use App\Repositories\Filters;
use App\Repositories\Pagination;
use Illuminate\Database\Eloquent\Builder;

class ProductSearch
{
    public static function apply(Request $filter)
    {
        $query = static::applyDecorationsFromRequest(Product::query(), $filter);
        return static::getResult($query, $filter);
    }

    private static function applyDecorationsFromRequest(Builder $query, Request $filter)
    {
        if ($filter->q) {
            $query = Filters\Search::apply($filter, new Product);
        }

        if ($filter->id) {
            $query = Filters\UploadId::apply($query, $filter->id, 'products');
        }

        if ($filter->p) {
            $query = Filters\Price::apply($query, $filter->p, 'products');
        }
        return $query;
    }

    private static function getResult(Builder $query, Request $filter)
    {
        if ($filter->sortBy) Pagination\Sort::apply($query, $filter);
        return Pagination\Paginate::apply($query, $filter);
    }
}
