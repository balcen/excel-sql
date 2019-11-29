<?php

namespace App\Http\Controllers\api;

use App\Order;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExcelFileUpload;

class OrdersController extends Controller
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
        if($sortBy = $request->sortBy) {
            $sortDesc = $request->sortDesc;
            $orders = Order::orderBy($sortBy, $sortDesc)
                ->paginate($itemsPerPage);
        } else {
            $orders = Order::paginate($itemsPerPage);
        }

        return response()->json($orders);

//        $orders = Order::all();
//        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = $request->all();
        $order['created_at'] = Carbon::now();
        $order['updated_at'] = Carbon::now();
        $result = Order::insert($order);
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
        $query = Order::where('id', '>=', $id);
        if ($sortBy = $request->sortBy) {
            $sortDesc = $request->sortDesc;
            $query = $query->orderBy($sortBy, $sortDesc);
        }
        $orders = $query->paginate($itemsPerPage);
        return response()->json($orders);
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
        $order = $request->all();
        $result = Order::find($id)->update($order);
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
        $result = Order::find($id)->delete();
        return response()->json(['result' => $result]);
    }

    public function deleteAll(Request $request)
    {
        $ids = explode(',', $request->ids);
        $result = Order::whereIn('id', $ids)->delete();
        return response()->json(['result' => $result]);
    }

    public function upload(Request $request)
    {
        $file['file'] = $request->file('file');
        $file['fileName'] = $request->file('file')->getClientOriginalName();
        $excelData = $this->getExcelData($file, 'orders');
        Order::insert($excelData);
        $id = DB::getPdo()->lastInsertId();
        return response()->json(['id'=>$id]);
    }

    public function searchAll(Request $request)
    {
        $q = urldecode($request->q);
        $itemsPerPage = $request->itemsPerPage;
        $query = Order::where('o_no', 'like', '%' . $q . '%')
            ->orWhere('o_date', 'like', '%' . $q . '%')
            ->orWhere('o_seller_name', 'like', '%' . $q . '%')
            ->orWhere('o_buyer_name', 'like', '%' . $q . '%')
            ->orWhere('o_product_name', 'like', '%' . $q . '%')
            ->orWhere('o_product_part_no', 'like', '%' . $q . '%')
            ->orWhere('o_product_spec', 'like', '%' . $q . '%')
            ->orWhere('o_product_price', 'like', '%' . $q . '%')
            ->orWhere('o_currency', 'like', '%' . $q . '%')
            ->orWhere('o_amount', 'like', '%' . $q . '%')
            ->orWhere('o_note', 'like', '%' . $q . '%');

        if($sortBy = $request->sortBy) {
            $sortDesc = $request->sortDesc;
            $query = $query->orderBy($sortBy, $sortDesc);
        }

        if ($id = $request->id) {
            $query = $query->where('id', '>=', $id);
        }

        $orders = $query->paginate($itemsPerPage);

        return response()->json($orders);
    }
}
