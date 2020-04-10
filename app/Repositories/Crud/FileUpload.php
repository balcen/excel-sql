<?php

namespace App\Repositories\Crud;

use Illuminate\Support\Facades\DB;
use App\Repositories\FIle\Row;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class FileUpload
{
    public function apply(Model $model, Request $request)
    {
        return $this->insertRow($model, $request);
    }

    private function insertRow(Model $model, Request $request)
    {
        $data = Row::apply($model, $request);
        $model::query()->insert($data);
        return DB::connection()->getPdo()->lastInsertId();
    }
}
