<?php

namespace App\Repositories\Products;

use App\Entities\Product;
use App\Repositories\Filters\Price;
use Illuminate\Http\Request;
use App\Repositories\Filters\Search;
use App\Repositories\Pagination\Sort;
use App\Repositories\Filters\UploadId;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\Pagination\Paginate;

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
            $query = Search::apply($filter, new Product);
        }

        if ($filter->id) {
            $query = UploadId::apply($query, $filter->id, 'products');
        }

        if ($filter->p) {
            $query = Price::apply($query, $filter->p, 'products');
        }
        return $query;
    }

    private static function getResult(Builder $query, Request $filter)
    {
        if ($filter->sortBy) Sort::apply($query, $filter);
        return Paginate::apply($query, $filter);
    }
}
