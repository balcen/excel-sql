<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;


trait ExcelFileUpload {
    private $worksheet;
    private $excelData;
    private $headers = [
        'clients' => ['c_no', 'c_name', 'c_class', 'c_contact', 'c_phone', 'c_mail'],
        'products' => ['p_type', 'p_name', 'p_part_no', 'p_spec', 'p_size', 'p_weight', 'p_price', 'p_note'],
        'orders' => ['o_no', 'o_date', 'o_s_name', 'o_b_name', 'o_p_name', 'o_p_no', 'o_spec', 'o_price', 'o_currency', 'o_count', 'o_amount', 'o_note'],
        'bills' => ['b_no', 'b_date', 'b_mature', 'b_o_no', 'b_s_name', 'b_b_name', 'b_p_name', 'b_p_no', 'b_spec', 'b_price', 'b_currency', 'b_count', 'b_amount', 'b_note']
    ];

    public function getExcelData($file, $type)
    {
        $this->worksheet = IOFactory::load($file)->getActiveSheet();
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

                $this->excelData[$i][$headers[$j++]] = $cellValue;
            }
//            $this->excelData[$i]['created_at'] = Carbon::now();
//            $this->excelData[$i]['updated_at'] = Carbon::now();
            $i++;
        }
    }
}
