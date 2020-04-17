<?php

namespace App\Services\Table;

use App\Entities\Order;
use App\Services\ExcelService;

class OrderService extends TableService
{
    protected $columns = ['o_no', 'o_date', 'o_seller_name', 'o_buyer_name', 'o_product_name', 'o_product_part_no',
        'o_product_spec', 'o_product_price', 'o_currency', 'o_quantity', 'o_amount', 'o_note'];

    function model()
    {
        return new Order();
    }

    function getColumns()
    {
        return $this->columns;
    }
}
