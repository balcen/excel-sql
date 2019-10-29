<?php

namespace App\Http\Controllers\api;

use App\Invoice;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExcelFileUpload;
use Illuminate\Validation\Rules\In;

class InvoicesController extends Controller
{
    use ExcelFileUpload;

    public function __construct()
    {
        $this->middleware('cors');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage;
        $invoices = Invoice::paginate($itemsPerPage);

        return response()->json($invoices);

//        $invoices = Invoice::all();
//        return response()->json($invoices);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $invoice = $request->all();
        $invoice['created_at'] = Carbon::now();
        $invoice['updated_at'] = Carbon::now();
        $result = Invoice::insert($invoice);
        return response()->json(['result'=>$result]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $invoice = $request->all();
        $result = Invoice::find($id)->update($invoice);
        return response()->json(['result'=>$result]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = Invoice::find($id)->delete();
        return response()->json(['result' => $result]);
    }

    public function deleteAll(Request $request)
    {
        $ids = explode(',', $request->ids);
        $result = Invoice::whereIn('id', $ids)->delete();
        return response()->json(['result'=>$result]);
    }

    public function upload(Request $request)
    {
        $file['file'] = $request->file('file');
        $file['fileName'] = $request->file('file')->getClientOriginalName();
        $excelData = $this->getExcelData($file, 'invoices');
        $result = Invoice::insert($excelData);
        return response()->json(['data'=>$excelData]);
    }
}
