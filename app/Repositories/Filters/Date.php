<?php

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

class Date implements Filter
{
    public static function apply(Builder $builder, $value, $table)
    {
        $column = substr($table, 0, 1) . '_date';

        if (!empty($value[0])) {
            $builder = $builder->where($column, '>=', $value[0]);
        }

        if (!empty($value[1])) {
            $builder = $builder->where($column, '<=', $value[1]);
        }

        return $builder;
    }
}
