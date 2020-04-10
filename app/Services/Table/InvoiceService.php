<?php

namespace App\Services\Table;

use App\Entities\Invoice;
use App\Services\ExcelService;

class InvoiceService extends TableService
{
    private $columns = ['i_no', 'i_date', 'i_mature', 'i_order_no', 'i_seller_name', 'i_buyer_name', 'i_product_name',
        'i_product_part_no', 'i_product_spec', 'i_product_price', 'i_currency', 'i_quantity', 'i_amount', 'i_note'];

    function model()
    {
        return new Invoice();
    }

    function getColumns()
    {
        return $this->columns;
    }
}
