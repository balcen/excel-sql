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
            $fileName = $file->getClientOriginalName();
            $id = $request->user()->id;
            $time = Carbon::now();
            $sheet = IOFactory::load($file)->getActiveSheet();
            if ($sheet->getHighestDataRow() > 500) {
                return ["error" => "超過 500 筆資料"];
            }
            $headerCount = count($this->header);

            $result = [];
            $i = 0;
            foreach ($sheet->getRowIterator(2) as $row) {
                if ($i < $sheet->getHighestDataRow() - 1) {
                    $j = 0;
                    foreach ($row->getCellIterator() as $cell) {
                        if ($j < $headerCount) {
                            $cellValue = $cell->getCalculatedValue();
                            if ($cellValue instanceof RichText) {
                                $cellValue = $cellValue->getPlainText();
                            }
                            if ($headerCount == 12 && $j == 1) {
                                $cellValue = Date::excelToDateTimeObject($cellValue);
                            }
                            if ($headerCount == 14 && ($j == 1 || $j == 2)) {
                                $cellValue = Date::excelToDateTimeObject($cellValue);
                            }
                            $result[$i][$this->header[$j++]] = $cellValue;
                        }
                    }
                    $result[$i]['user_id'] = $id;
                    $result[$i]['file_name'] = $fileName;
                    $result[$i]['created_at'] = $time;
                    $result[$i]['updated_at'] = $time;
                    $i++;
                }
            }
            dd($result);
            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getDataFromSpout()
    {

    }
}
