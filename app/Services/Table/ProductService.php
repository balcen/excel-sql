<?php

namespace App\Services\Table;

use App\Entities\Product;

class ProductService extends TableService
{
    protected $columns = ['p_type', 'p_name', 'p_part_no', 'p_spec', 'p_size', 'p_weight', 'p_price', 'p_note'];

    function model()
    {
        return new Product();
    }

    function getColumns()
    {
        return $this->columns;
    }
}
