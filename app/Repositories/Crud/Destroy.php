<?php

namespace App\Repositories\Crud;

use Illuminate\Database\Eloquent\Model;

class Destroy
{
    public static function apply(Model $model, $id)
    {
        try {
            return $model->newQuery()->find($id)->delete();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
