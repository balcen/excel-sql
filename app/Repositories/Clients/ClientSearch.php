<?php

namespace App\Repositories\Clients;

use App\Entities\Client;
use Illuminate\Http\Request;
use App\Repositories\Filters\Search;
use App\Repositories\Filters\UploadId;
use App\Repositories\Pagination\Paginate;
use Illuminate\Database\Eloquent\Builder;

class ClientSearch
{
    public static function apply(Request $filter)
    {
        $query = static::applyDecorationsFromRequest(Client::query(), $filter);
        return static::getResult($query, $filter);
    }

    private static function applyDecorationsFromRequest(Builder $query, Request $filter)
    {
        if ($filter->q) {
            $query = Search::apply($filter, new Client);
        }

        if ($filter->id) {
            $query = UploadId::apply($query, $filter->id, 'clients');
        }
        return $query;
    }

    private static function getResult(Builder $query, Request $filter)
    {
        return Paginate::apply($query, $filter);
    }
}
