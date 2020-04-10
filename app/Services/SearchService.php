<?php


namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class SearchService
{
    /**
     * @var $model Model;
     */
    private $model;

    /**
     * @var $builder Builder;
     */
    private $builder;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param Request $request
     * @return Builder
     */

    public function getData(Request $request)
    {
        $this->builder = $this->model::query();

        if ($request->query("q")) {
            $this->getSearchQuery($request->query("q"));
        }
        if ($request->query("id")) {
            $this->getSearchId($request->query("id"));
        }
        if ($request->query("p")) {
            $this->getSearchRange($request->query("p"), "price");
        }
        if ($request->query("a")) {
            $this->getSearchRange($request->query("a"), "amount");
        }
        if ($request->query("d")) {
            $this->getSearchRange($request->query("d"), "date");
        }
        if ($request->query("ed")) {
            $this->getSearchRange($request->query("ed"), "mature");
        }

        return $this->builder;
    }

    private function getSearchQuery($queryString)
    {
        $queryString = "%$queryString%";
        $fillable = $this->model->getFillable();
        $columns = array_splice($fillable, 0, -2);
        foreach ($columns as $column) {
            $this->builder = $this->builder->orWhere($column, "like", $queryString);
        }
    }

    private function getSearchId($id)
    {
        $this->builder->where("id", ">=", $id);
    }

    private function getSearchRange($arr, $column)
    {
        $columnArr = preg_grep("/{$column}$/", $this->model->getFillable());
        if (count($columnArr) == 1) {
            $targetColumn = array_values($columnArr)[0];
            if (!empty($arr[0])) {
                $this->builder->where($targetColumn, ">=", $arr[0]);
            }

            if (!empty($arr[1])) {
                $this->builder->where($targetColumn, "<=", $arr[1]);
            }
        }

    }
}
