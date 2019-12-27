<?php

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    /**
     * 把過濾的條件加在 Builder 的實例上
     *
     * @param Builder $builder
     * @param mixed $value
     * @param String $table
     *
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value, $table);
}
