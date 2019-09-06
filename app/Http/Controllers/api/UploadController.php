<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadController extends Controller
{
    public function import (Request $request) {
        $worksheet = IOFactory::load($request->file('file'))->getActiveSheet()->toArray();
        $worksheet = $this->arrayFilter($worksheet);
        $length = count($worksheet);
        $type = $this->getDataType($worksheet);
        return response()->json([ 'type' =>$type, 'length' => $length -1 ]);
    }

    private function getDataType($worksheet)
    {
        switch (count($worksheet[0])) {
        case '6':
            return 'clients';
        case '8':
            return 'products';
        case '12':
            return 'orders';
        case '14':
            return 'invoices';
        default:
            return false;
        }
    }

    private function arrayFilter($array) {
        $array = array_filter(array_map('array_filter', $array));
        return $array;
    }
}
