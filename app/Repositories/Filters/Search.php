<?php

namespace App\Repositories\Filters;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Search
{
    public static function apply(Request $filter, Model $model)
    {
        $q = static::query($filter);
        $columns = static::getColumns($model);

        return $model->newQuery()
            ->where(
                function(EloquentBuilder $bd) use ($columns, $q) {
                    foreach ($columns as $column) {
                        $bd->orWhere($column, 'like', $q);
                    }
                }
            );
    }

    private static function getColumns(Model $model)
    {
        $array = $model->getFillable();
        return array_splice($array, 0, -2);
    }

    private static function query(Request $filter)
    {
        return '%' . $filter->q . '%';
    }
}
