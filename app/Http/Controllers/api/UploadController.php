<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UploadController extends Controller
{
    protected $status = [
        'datatype' => '',
        'col' => ''
    ];

    public function import (Request $request) {
        $worksheet = IOFactory::load($request->file('file'))->getActiveSheet()->toArray();
        $worksheet = $this->arrayFilter($worksheet);
        $length = count($worksheet);
        $this->getDataType($worksheet);
        return response()->json([ 'type' => $this->status['datatype'], 'length' => $length -1, 'col' => $this->status['col']]);
    }

    private function getDataType($worksheet)
    {
        // 如果 $len 出現異常就 flash 檔案錯誤
        if(!$len = count($worksheet[0])) {
            return false;
        }

        $this->status['col'] = $len;

        switch ($len) {
        case '6':
            $this->status['datatype'] = 'clients';
//            return 'clients';
            break;
        case '8':
            $this->status['datatype'] = 'products';
//            return 'products';
            break;
        case '12':
            $this->status['datatype'] = 'orders';
//            return 'orders';
            break;
        case '14':
            $this->status['datatype'] = 'invoices';
//            return 'invoices';
            break;
        default:
            $this->status['datatype'] = 'none';
//            return $len;
            break;
        }
    }

    private function arrayFilter($array) {
        $array = array_filter(array_map('array_filter', $array));
        return $array;
    }
}
