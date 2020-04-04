<?php

namespace App\Services\Table;

use App\Entities\Client;

class ClientService extends TableService
{
    function model()
    {
        return new Client();
    }

    function getColumn()
    {
        return [];
    }
}
