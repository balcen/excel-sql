<?php

namespace App\Http\Controllers\api;

use App\Services\Table\ClientService;
use App\Services\Table\ProductService;

class ProductsController extends BaseTableController
{
    public function __construct(ProductService $service)
    {
        parent::__construct($service);
    }
}
