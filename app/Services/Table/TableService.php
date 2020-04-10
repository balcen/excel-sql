<?php

namespace App\Services\Table;

use Illuminate\Http\Request;
use App\Services\ExcelService;
use App\Services\SearchService;
use Illuminate\Database\Eloquent\Model;

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

    public function index(Request $request)
    {
        $query = $this->model->newQuery();
        if (!is_null($request->query('sortBy'))) {
            $orderBy = $request->query('sortBy')[0];
            $orderDesc = $request->query('sortDesc')[0] === 'true' ? 'desc' : 'asc';
            $query->orderBy($orderBy, $orderDesc);
        }

        if ($request->user()->permission < 4) {
            $query->where('user_id', $request->user()->id)
                ->orWhere('user_id', null);
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
        $excelService = new ExcelService($this->columns);
        $data = $excelService->getData($request);
        $this->model::query()->insert($data);
        return ["id" => $this->model->getConnection()->getPdo()->lastInsertId()];
    }

    public function search(Request $request)
    {
        $searchService = new SearchService($this->model);
        $data = $searchService->getData($request);
        return $data->paginate($request->query("itemsPerPage"));
    }
}
