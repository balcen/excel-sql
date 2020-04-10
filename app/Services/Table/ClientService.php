<?php

namespace App\Services\Table;

use App\Entities\Client;
use App\Services\ExcelService;

class ClientService extends TableService
{
    protected $columns = ['c_tax_id', 'c_name', 'c_type', 'c_contact', 'c_phone', 'c_mail'];

    function model()
    {
        return new Client();
    }

    function getColumns()
    {
        return $this->columns;
    }
}
