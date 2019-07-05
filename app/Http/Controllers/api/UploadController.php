<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class UploadController extends Controller
{
    private $excel;
    private $worksheet;
    public $excel_data = [];
    private $header = [
        'clients' => ['c_no', 'c_name', 'c_class', 'c_contact', 'c_phone', 'c_mail'],
        'products' => ['p_class', 'p_name', 'p_no', 'p_spec', 'p_size', 'p_weight', 'p_price', 'p_note'],
        'orders' => ['o_no', 'o_date', 'o_s_name', 'o_b_name', 'o_p_name', 'o_p_no', 'o_spec', 'o_price', 'o_currency', 'o_count', 'o_amount', 'o_note'],
        'bills' => ['b_no', 'b_date', 'b_mature', 'b_o_no', 'b_s_name', 'b_b_name', 'b_p_name', 'b_p_no', 'b_spec', 'b_price', 'b_currency', 'b_count', 'b_amount', 'b_note']
    ];

    // public function import (Request $request)
    // {
    //     dd($request->all());
    //     $this->excel = IOFactory::load($request->file());
    //     $this->worksheet = $this->excel->getActiveSheet();
    //     $this->getImage();
    //     $data = $this->getData();
    //     return response()->json($data);
    // }

    public function import (Request $request) {
        dd($request);
        $array = IOFactory::load($request->all())->getActiveSheet()->toArray();
        dd($array);
    }

    private function getImage () {
        foreach ($this->work_sheet->getDrawingCollection() as $drawing) {
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
            Storage::disk('public')->put($myFileName, $imageContents);
            // 因為Excel從1開始而第2行又是title，所以-2
            $this->excel_data[$startRow-2] = [
                'image' => $myFileName
            ];
        }
    }

    private function getData () {
        $i = 0;
        foreach ($this->work_sheet->getRowIterator(2) as $row) {
            $j = 0;
            foreach ($row->getCellIterator() as $key => $cell) {
                if($key == 1 && $cell->getValue() == null) break 2;
                $cellValue = $cell->getValue();
                // 如果是RichText就使用getPlainText方法
                if ($cellValue instanceof RichText) {
                    $this->excel_data[$i][$array[$j]] = $cellValue->getPlainText();
                } else {
                    if ($string === 'products') {
                        if ($j > 9) break;
                        if ($j == 6) $cellValue = round($cellValue, 4);
                        if ($j == 5) {
                            $cellValue = str_replace('KG','', $cellValue);
                            $cellValue = str_replace('kg', '', $cellValue);
                        }
                    }else if ($string === 'bills') {
                        if ($j < 13) break;
                        if ($j == 1 || $j == 2) {
                            $UNIX_DATE = ($cellValue - 25569) * 86400;
                            $cellValue = gmdate('Y-m-d', $UNIX_DATE);
                        }
                    }else if ($string === 'orders') {
                        if ($j > 11) break;
                        if ($j == 1) {
                            $UNIX_DATE = ($cellValue - 25569) * 86400;
                            $cellValue = gmdate('Y-m-d', $UNIX_DATE);
                        }
                        if ($j == 10) $cellValue = $this->excel_data[$i]['o_price'] * $this->excel_data[$i]['o_count'];
                    }else if ($string === 'clients') {
                        if ($j <5) break;
                    }
                    $this->excel_data[$i][$array[$j]] = $cellValue;
//                    $this->excel_data[$i][] = $cellValue;
                }
                $this->excel_data[$i]['created_at'] = date('Y-m-d H:i:s');
                $this->excel_data[$i]['updated_at'] = date('Y-m-d H:i:s');
                $j++;
            }
            $i++;
        }
        return $this->excel_data;
    }
}
