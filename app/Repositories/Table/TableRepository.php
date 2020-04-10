<?php

namespace App\Repositories\Table;

use Illuminate\Database\Eloquent\Model;

abstract class TableRepository
{
    /**
     * @var Model $model
     */
    private $model;
    private $filter = null;

    public function __construct()
    {
        $this->model = $this->model();
    }

    abstract function model();

    public function index()
    {
        $this->model::all();
    }
}
