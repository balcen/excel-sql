<?php

namespace App\Services\Table;

use App\Entities\Product;

class ProductService extends TableService
{
    function model()
    {
        return new Product();
    }

    function getColumn()
    {
        return [];
    }
}
