<?php

namespace App\Repositories\Invoices;

use App\Entities\Invoice;
use App\Repositories\Crud;
use Illuminate\Http\Request;

class InvoiceResource
{
    private static $invoice;

    public function __construct()
    {
        if (!self::$invoice instanceof Invoice) {
            self::$invoice = new Invoice();
        }
    }

    public static function index(Request $request)
    {
        return Crud\Index::apply(new Invoice(), $request);
    }

    public static function store(Request $request)
    {
        return Crud\Store::apply(self::invoice, $request);
    }

    public static function update(Request $request, $id)
    {
        return Crud\Update::apply(self::$invoice, $request, $id);
    }

    public static function destroy($id)
    {
        return Crud\Destroy::apply(self::$invoice, $id);
    }

    public static function batchDelete(Request $request)
    {
        return Crud\BatchDelete::apply(self::$invoice, $request);
    }

    public static function upload(Request $request)
    {
        return Crud\FileUpload::getInstance()->apply(self::$invoice, $request);
    }
}
