<?php

namespace App\Repositories\Invoices;

use App\Entities\Invoice;
use App\Repositories\Crud;
use Illuminate\Http\Request;

class InvoiceResource
{
    public static function index(Request $request)
    {
        return Crud\Index::apply(new Invoice, $request);
    }

    public static function store(Request $request)
    {
        return Crud\Store::apply(new Invoice, $request);
    }

    public static function update(Request $request, $id)
    {
        return Crud\Update::apply(new Invoice, $request, $id);
    }

    public static function destroy($id)
    {
        return Crud\Destroy::apply(new Invoice, $id);
    }

    public static function batchDelete(Request $request)
    {
        return Crud\BatchDelete::apply(new Invoice, $request);
    }

    public static function upload(Request $request)
    {
        return Crud\FileUpload::apply(new Invoice, $request);
    }
}
