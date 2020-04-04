<?php

namespace App\Http\Controllers\api;

use App\Services\Table\OrderService;

class OrdersController extends BaseTableController
{
    public function __construct(OrderService $service)
    {
        parent::__construct($service);
    }
}
