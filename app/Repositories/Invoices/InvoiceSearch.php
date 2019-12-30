<?php

namespace App\Repositories\Invoices;

use App\Entities\Invoice;
use Illuminate\Http\Request;
use App\Repositories\Filters;
use App\Repositories\Pagination;
use Illuminate\Database\Eloquent\Builder;

class InvoiceSearch
{
    public static function apply(Request $filter)
    {
        $query = static::applyDecorationsFromRequest(Invoice::query(), $filter);
        return static::getResult($query, $filter);
    }

    private static function applyDecorationsFromRequest(Builder $query, Request $filter)
    {
        if ($filter->q) {
            $query = Filters\Search::apply($filter, new Invoice);
        }

        if ($filter->id) {
            $query = Filters\UploadId::apply($query, $filter->id, 'invoices');
        }

        if ($filter->p) {
            $query = Filters\Price::apply($query, $filter->p, 'invoices');
        }

        if ($filter->a) {
            $query = Filters\Amount::apply($query, $filter->a, 'invoices');
        }

        if ($filter->d) {
            $query = Filters\Date::apply($query, $filter->a, 'invocies');
        }

        if ($filter->ed) {
            $query = Filters\ExpireDate::apply($query, $filter->ed, 'invoices');
        }
        return $query;
    }

    private static function getResult(Builder $query, Request $filter)
    {
        if ($filter->sortBy) Pagination\Sort::apply($query, $filter);
        return Pagination\Paginate::apply($query, $filter);
    }
}
