<?php

namespace App\Services\Table;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

abstract class TableService
{
    /**
     * @var $model Model;
     */
    protected $model;

    public function __construct()
    {
        $this->model = $this->model();
    }

    abstract function model();

    abstract function getColumn();

    public function index(Request $request)
    {
        $query = $this->model->newQuery();
        if (!is_null($request->query('sortBy'))) {
            $orderBy = $request->query('sortBy')[0];
            $orderDesc = $request->query('sortDesc')[0] === 'true' ? 'desc' : 'asc';
            $query->orderBy($orderBy, $orderDesc);
        }

        if ($request->user()->permission < 4) {
            $query->where('author', $request->user()->id)
                ->orWhere('author', null);
        }
        return $query->paginate($request->query('itemsPerPage'));
    }

    public function store(Request $request)
    {
        $row = $request->all();
        $row['author'] = $request->user()->id;
        return $this->model->newQuery()->create($row);
    }

    public function show(Request $request, $id)
    {
    }

    public function update(Request $request, $id)
    {
        return $this->model->newQuery()->find($id)->update($request->all());
    }

    public function destroy($id)
    {
        return $this->model::destroy($id);
    }


    public function batchDelete(Request $request)
    {
        return $this->model::destroy($request->input('ids'));
    }

    public function upload(Request $request)
    {
    }

    public function searchAll(Request $request)
    {
    }
}
