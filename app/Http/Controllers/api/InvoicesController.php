<?php


namespace App\Http\Controllers\api;

use App\Services\Table\InvoiceService;

class InvoicesController extends BaseTableController
{
     public function __construct(InvoiceService $service)
    {
        parent::__construct($service);
    }
}
