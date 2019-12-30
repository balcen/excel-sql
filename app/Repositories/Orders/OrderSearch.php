<?php

namespace App\Repositories\Orders;

use App\Entities\Order;
use Illuminate\Http\Request;
use App\Repositories\Filters;
use App\Repositories\Pagination;
use Illuminate\Database\Eloquent\Builder;

class OrderSearch
{
    public static function apply(Request $filter)
    {
        $query = static::applyDecorationsFromRequest(Order::query(), $filter);
        return static::getResult($query, $filter);
    }

    private static function applyDecorationsFromRequest(Builder $query, Request $filter)
    {
        if ($filter->q) {
            $query = Filters\Search::apply($filter, new Order);
        }

        if ($filter->id) {
            $query = Filters\UploadId::apply($query, $filter->id, 'orders');
        }

        if ($filter->p) {
            $query = Filters\Price::apply($query, $filter->p, 'orders');
        }

        if ($filter->a) {
            $query = Filters\Amount::apply($query, $filter->a, 'orders');
        }

        if ($filter->d) {
            $query = Filters\Date::apply($query, $filter->d, 'orders');
        }
        return $query;
    }

    private static function getResult(Builder $query, Request $filter)
    {
        if ($filter->sortBy) Pagination\Sort::apply($query, $filter);
        return Pagination\Paginate::apply($query, $filter);
    }
}
