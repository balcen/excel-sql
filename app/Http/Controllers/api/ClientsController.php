<?php

namespace App\Http\Controllers\api;

use App\Services\Table\ClientService;

class ClientsController extends BaseTableController
{
    public function __construct(ClientService $service)
    {
        parent::__construct($service);
    }
}
