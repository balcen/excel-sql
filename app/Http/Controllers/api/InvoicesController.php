<?php

namespace App\Http\Controllers\api;

use App\Invoice;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExcelFileUpload;

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
        if ($sortBy = $request->sortBy) {
            $sortDesc = $request->sortDesc;
            $invoices = Invoice::orderBy($sortBy, $sortDesc)
                ->paginate($itemsPerPage);
        } else {
            $invoices = Invoice::paginate($itemsPerPage);
        }

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
    public function show(Request $request, $id)
    {
        $itemsPerPage = $request->itemsPerPage;
        $query = Invoice::where('id', '>=', $id);
        if($sortBy = $request->sortBay) {
            $sortDesc = $request->sortDesc;
            $query = $query->orderBy($sortBy, $sortDesc);
        }
        $invoice = $query->paginate($itemsPerPage);
        return response()->json($invoice);
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
        Invoice::insert($excelData);
        $id = DB::getPdo()->lastInsertId();
        return response()->json(['id'=>$id]);
    }

    public function searchAll(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage;
        $query = Invoice::query();

        if ($request->q) {
            $q = urldecode($request->q);
            $query = $query->where(function($qy) use ($q) {
                $qy = $qy->where('i_no', 'like', '%' . $q . '%')
                    ->orWhere('i_date', 'like', '%' . $q . '%')
                    ->orWhere('i_mature', 'like', '%' . $q . '%')
                    ->orWhere('i_order_no', 'like', '%' . $q . '%')
                    ->orWhere('i_seller_name', 'like', '%' . $q . '%')
                    ->orWhere('i_buyer_name', 'like', '%' . $q . '%')
                    ->orWhere('i_product_name', 'like', '%' . $q . '%')
                    ->orWhere('i_product_part_no', 'like', '%' . $q . '%')
                    ->orWhere('i_product_spec', 'like', '%' . $q . '%')
                    ->orWhere('i_product_price', 'like', '%' . $q . '%')
                    ->orWhere('i_currency', 'like', '%' . $q . '%')
                    ->orWhere('i_quantity', 'like', '%' . $q . '%')
                    ->orWhere('i_amount', 'like', '%' . $q . '%')
                    ->orWhere('i_note', 'like', '%' . $q . '%');
            });
        }

        if ($id = $request->id) {
            $query = $query->where('id', '>=', $id);
        }

        if ($request->p) {
            $p = preg_split('~,~', $request->p);
            if (empty($p[1])) {
                $query = $query->where('i_product_price', '>=', $p[0]);
            } else {
                $query = $query->where('i_product_price', '<=', $p[1]);
            }
        }

        if ($request->a) {
            $a = preg_split('~,~', $request->a);

            if (empty($a[1])) {
                $query = $query->where('i_quantity', '>=', $a[0]);
            } else {
                $query = $query->where('i_quantity', '<=', $a[1]);
            }

        }

        if ($request->d) {
            $d = preg_split('~,~', $request->d);
            if (empty($d[1])) {
                $query = $query->where('i_date', '>=', $d[0]);
            } else {
                $query = $query->where('i_date', '<=', $d[1]);
            }
        }

        if ($request->ed) {
            $ed = preg_split('~,~', $request->ed);
            if (empty($ed[1])) {
                $query = $query->where('i_mature', '>=', $ed[0]);
            } else {
                $query = $query->where('i_mature', '<=', $ed[1]);
            }
        }

        if($sortBy = $request->sortBy) {
            $sortDesc = $request->sortDesc;
            $query = $query->orderBy($sortBy, $sortDesc);
        }


        $invoices = $query->paginate($itemsPerPage);

        return response()->json($invoices);
    }
}
