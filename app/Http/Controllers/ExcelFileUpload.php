<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


trait ExcelFileUpload {
    private $worksheet;
    private $excelData;
    private $fileName;
    private $headers = [
        'clients' => ['c_tax_id', 'c_name', 'c_type', 'c_contact', 'c_phone', 'c_mail'],
        'products' => ['p_type', 'p_name', 'p_part_no', 'p_spec', 'p_size', 'p_weight', 'p_price', 'p_note'],
        'orders' => ['o_no', 'o_date', 'o_seller_name', 'o_buyer_name', 'o_product_name', 'o_product_part_no', 'o_product_spec', 'o_product_price', 'o_currency', 'o_quantity', 'o_amount', 'o_note'],
        'invoices' => ['i_no', 'i_date', 'i_mature', 'i_order_no', 'i_seller_name', 'i_buyer_name', 'i_product_name', 'i_product_part_no', 'i_product_spec', 'i_product_price', 'i_currency', 'i_quantity', 'i_amount', 'i_note']
    ];

    public function getExcelData($file, $type)
    {
        $this->fileName = $file['fileName'];
        $this->worksheet = IOFactory::load($file['file'])->getActiveSheet();
        $this->getImage();
        $this->getRowData($type);

        return $this->excelData;
    }

    private function getImage ()
    {
        foreach ($this->worksheet->getDrawingCollection() as $drawing) {
            // Get column & row
            list ($startColumn, $startRow) = Coordinate::coordinateFromString($drawing->getCoordinates());
            if($drawing instanceof MemoryDrawing) {
                ob_start();
                call_user_func(
                    $drawing->getRenderingFunction(),
                    $drawing->getImageResource()
                );
                $imageContents = ob_get_contents();
                ob_end_clean();
                switch ($drawing->getMimeType()) {
                    case MemoryDrawing::MIMETYPE_PNG:
                        $extension = 'png';
                        break;
                    case MemoryDrawing::MIMETYPE_GIF:
                        $extension = 'gif';
                        break;
                    case MemoryDrawing::MIMETYPE_JPEG:
                        $extension = 'jpeg';
                        break;
                }
            } else {
                $zipReader = fopen($drawing->getPath(), 'r');
                $imageContents = '';
                while (!feof($zipReader)) {
                    $imageContents .= fread($zipReader, 1024);
                }
                fclose($zipReader);
                $extension = $drawing->getExtension();
            }
            $myFileName = '00_Image_'. uniqid() . '.' . $extension;
            Storage::disk('public/')->put($myFileName, $imageContents);
            // 因為Excel從1開始而第2行又是title，所以-2
            $this->excelData[$startRow-2] = [
                'p_image' => $myFileName
            ];
        }
    }

    private function getRowData($type) {
        $headers = $this->headers[$type];
        $i = 0;
        foreach ($this->worksheet->getRowIterator(2) as $row) {
            $j = 0;
            foreach ($row->getCellIterator() as $key => $cell) {
                if ($key >= 1 && $cell->getValue() == null) break 2;

                $cellValue = $cell->getValue();

                if ($cellValue instanceof RichText) {
                    $cellValue = $cellValue->getPlainText();
                }

                switch($type) {
                case 'orders':
                   if ($j > 11) break 2;
                   if ($j === 1) $cellValue = Date::excelToDateTimeObject($cellValue);
                   if ($j === 10) {
                       $cellValue = $this->excelData[$i]['o_product_price'] * $this->excelData[$i]['o_quantity'];
                   }
                   break;
                case 'invoices':
                    // Excel 日期換成 php 日期
                    if ($j === 1 || $j === 2) {
                        $cellValue = Date::excelToDateTimeObject($cellValue);
                    }
                    break;
                }

                $this->excelData[$i][$headers[$j++]] = $cellValue;
            }
            $this->excelData[$i]['created_at'] = Carbon::now();
            $this->excelData[$i]['updated_at'] = Carbon::now();
            $this->excelData[$i]['file_name'] = $this->fileName;
            $i++;
        }
    }
}
