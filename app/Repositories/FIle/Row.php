<?php

namespace App\Repositories\FIle;

use Carbon\Carbon;
use App\Entities\Order;
use App\Entities\Invoice;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Row
{
    public static function apply(Model $model, Request $request)
    {
        $header = Header::apply($model);
        return static::getRowData($request, $header, $model);
    }


    private static function getRowData(Request $request, $header, Model $model)
    {
        $file = static::readFile($request);
        $fileName = $file->getClientOriginalName();
        $worksheet = static::getWorksheet($file);

        $excelData = [];
        $i = 0;
        foreach ($worksheet->getRowIterator(2) as $row) {
            if ($i >= $worksheet->getHighestDataRow() -1) break;
            $j = 0;
            foreach ($row->getCellIterator() as $col => $cell) {
                if ($j >= count($header)) break;

                try {
                    $cellValue = $cell->getCalculatedValue();
                } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                    return $e->getMessage();
                }

                if ($cellValue instanceof RichText) {
                    $cellValue = $cellValue->getPlainText();
                };

                $className = static::getClassName($model);
                if ($className === 'Order' && $j === 1 || $className === 'Invoice' && $j === 1 || $className === 'Invoice' && $j === 2) {
                    try {
                        $cellValue = Date::excelToDateTimeObject($cellValue);
                    } catch (\Exception $e) {
                        return $e->getMessage();
                    }
                }

                $excelData[$i][$header[$j++]] = $cellValue;
            }
            $excelData[$i]['created_at'] = Carbon::now();
            $excelData[$i]['updated_at'] = Carbon::now();
            $excelData[$i]['file_name'] = $fileName;
            // 儲存 User ID
            $excelData[$i]['author'] = $request->user()->id;
            $i++;
        }
        return $excelData;
    }

    private static function readFile(Request $request)
    {
       return $request->file('file');
    }

    private static function getWorksheet($file)
    {
        try {
            return IOFactory::load($file)->getActiveSheet();
        } catch (Exception $e) {
            return $e->getMessage();
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $e->getMessage();
        }
    }

    private static function getClassName(Model $model)
    {
        try {
            $reflect = new \ReflectionClass($model);
            return $reflect->getShortName();
        } catch (\ReflectionException $e) {
            return $e->getMessage();
        }
    }
}
