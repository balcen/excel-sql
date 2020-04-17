<?php


namespace App\Services;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;

class FileService
{
    private $types = [6 => 'clients', 8 => 'products', 12 => 'orders', 14 => 'invoices'];

    public function import(Request $request)
    {
        try {
            $data = IOFactory::load($request->file('file'))->getActiveSheet()->toArray();
            $num = count(array_filter($data[0]));
            if (count($data) > 501) {
                return ['error' => '上傳檔案不能超過 500 筆'];
            }
            return [
                'type' => $this->types[$num] ?? 'none',
                'rows_num' => count($data) - 1,
                'cols_num' => $num,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
