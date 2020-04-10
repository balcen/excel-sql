<?php

namespace App\Repositories\File;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;

class Type
{
    public static function apply(Request $request)
    {
        return self::getDataStructure($request);
    }

    private static function getDataStructure(Request $request)
    {
        $array = self::getWorkSheet($request);
        $columns = array_filter($array[0]);
        return [
            'type' => self::switchType($columns),
            'length' => count($array) - 1,
            'col' => count($columns)
        ];
    }

    private static function getWorkSheet(Request $request)
    {
        try {
            return IOFactory::load($request->file())->getActiveSheet()->toArray();
        } catch (Exception $e) {
            return $e->getMessage();
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            return $e->getMessage();
        }
    }

    public static function switchType($array)
    {
        switch (count($array)) {
            case '6':
                return 'clients';
                break;
            case '8':
                return 'products';
                break;
            case '12':
                return 'orders';
                break;
            case '14':
                return 'invoices';
                break;
            default:
                return 'none';
                break;
        }
    }
}
