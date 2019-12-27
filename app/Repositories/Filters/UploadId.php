<?php

namespace App\Repositories\Filters;

use Illuminate\Database\Eloquent\Builder;

class UploadId implements Filter
{
    public static function apply(Builder $builder, $value, $table)
    {
        return $builder->where('id', '>=', $value);
    }
}
