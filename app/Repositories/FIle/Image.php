<?php

namespace App\Repositories\FIle;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class Image
{
    public static function apply(Request $request, $data)
    {
        return static::getImage($request, $data);
    }

    private static function getImage(Request $request, $data)
    {
        $worksheet = static::getWorksheet($request);
        return static::loopImage($worksheet, $data);

    }

    private static function getWorksheet(Request $request)
    {
        try {
            return IOFactory::load($request->file('file'))->getActiveSheet();
        } catch (Exception $e) {
            return $e->getMessage();
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $e->getMessage();
        }
    }

    private static function loopImage(Worksheet\Worksheet $worksheet, $data)
    {
        foreach ($worksheet->getDrawingCollection() as $drawing) {
            list ($startColumn, $startRow) = static::getLocate($drawing);
            list ($imageContents, $extension) = static::getImageContentAndExtension($drawing);
            $imageName = StorageImage::apply($imageContents, $extension);
            $data[$startRow-2] = [
                'p_image' => $imageName
            ];
        }
        return $data;
    }

    private static function getLocate(Worksheet\BaseDrawing $drawing)
    {
        try {
            return Coordinate::coordinateFromString($drawing->getCoordinates());
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $e->getMessage();
        }
    }

    private static function getImageContentAndExtension(Worksheet\BaseDrawing $drawing)
    {
        $imageContents = '';
        $extension = '';
        if ($drawing instanceof Worksheet\MemoryDrawing) {
            ob_start();
            call_user_func(
                $drawing->getRenderingFunction(),
                $drawing->getImageResource()
            );
            $imageContents = ob_get_contents();
            ob_end_clean();
            switch ($drawing->getMimeType()) {
                case Worksheet\MemoryDrawing::MIMETYPE_PNG:
                    $extension = 'png';
                    break;
                case Worksheet\MemoryDrawing::MIMETYPE_GIF:
                    $extension = 'gif';
                    break;
                case Worksheet\MemoryDrawing::MIMETYPE_JPEG:
                    $extension = 'jpeg';
                    break;
                default:
                    $extension = 'png';
            }
        } else if ($drawing instanceof Drawing) {
            $zipReader = fopen($drawing->getPath(), 'r');
            while (!feof($zipReader)) {
                $imageContents .= fread($zipReader, 1024);
            }
            fclose($zipReader);
            $extension = $drawing->getExtension();
        }
        return array($imageContents, $extension);
    }
}
