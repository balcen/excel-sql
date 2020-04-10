<?php


namespace App\Services;

use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ExcelService
{
    private $header;

    public function __construct($header)
    {
        $this->header = $header;
    }

    public function getData(Request $request)
    {
        try {
            $file = $request->file('file');
            $sheet = IOFactory::load($file)->getActiveSheet();
            $header_count = count($this->header);

            $result = [];
            $i = 0;
            foreach ($sheet->getRowIterator(2) as $row) {
                if ($i < $sheet->getHighestDataRow() - 1) {
                    $j = 0;
                    foreach ($row->getCellIterator() as $cell) {
                        if ($j < $header_count) {
                            $cellValue = $cell->getCalculatedValue();
                            if ($cellValue instanceof RichText) {
                                $cellValue = $cellValue->getPlainText();
                            }
                            if ($j == 1 &&  $header_count == 12 || $j == 1 && $header_count == 14 || $j == 2 && $header_count == 14) {
                                $cellValue = Date::excelToDateTimeObject($cellValue);
                            }
                            $result[$i][$this->header[$j++]] = $cellValue;
                        }
                    }
                    $result[$i]['user_id'] = $request->user()->id;
                    $result[$i]['file_name'] = $file->getClientOriginalName();
                    $result[$i]['created_at'] = Carbon::now();
                    $result[$i]['updated_at'] = Carbon::now();
                    $i++;
                }
            }
            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
