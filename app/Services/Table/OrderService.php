<?php

namespace App\Services\Table;

use App\Entities\Order;

class OrderService extends TableService
{
    function model()
    {
        return new Order();
    }

    function getColumn()
    {
        return [];
    }
}
