<?php

namespace App\Http\Controllers\api;

use App\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ExcelFileUpload;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
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
            $products = Product::orderBy($sortBy, $sortDesc)
                ->paginate($itemsPerPage);
        } else {
            $products = Product::paginate($itemsPerPage);
        }

        return response()->json($products);

//        $products = Product::all();
//        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $image = $request->file('image');
        $product = json_decode($request->all()['item'], true);
        $product['created_at'] = Carbon::now();
        $product['updated_at'] = Carbon::now();
        // if(isset($image)) {
        //     $image = $request->file('image');
        //     $imageName = '00_Image_'.uniqid().'.'.$image->getClientOriginalExtension();
        //     $imageContent = file_get_contents($image);
        //     $product['p_image'] = $imageName;
        // }
        $result = Product::insert($product);
        // Storage::disk('pubic')->put($imageName, $imageContent);
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
        $query = Product::where('id', '>=', $id);
        if($sortBy = $request->sortBy) {
            $sortDesc = $request->sortDesc;
            $query = $query->orderBy($sortBy, $sortDesc);
        }
        $products= $query->paginate($itemsPerPage);
        return response()->json($products);
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
        $product = $request->all();
        $result = Product::find($id)->update($product);
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
        $product = Product::find($id);
//        $image = $product->value('p_image');
        if($image = $product->value('p_image')) {
            Storage::disk('public')->delete($product->value('p_image'));
        }
        $result = $product->delete();
        return response()->json(['result' => $result]);
    }

    public function deleteAll(Request $request)
    {
        $ids = explode(',', $request->ids);
        $result = Product::whereIn('id', $ids)->delete();
        return response()->json(['result'=>$result]);
    }

    public function upload(Request $request)
    {
        try {
            $file['file'] = $request->file('file');
            $file['fileName'] = $request->file('file')->getClientOriginalName();
            $excelData = $this->getExcelData($file, 'products');
            Product::insert($excelData);
            $id = DB::getPdo()->lastInsertId();
        } catch (\Exception $e) {
            return response()->json($e);
        }
        return response()->json(['id' => $id]);
    }

    public function searchAll(Request $request)
    {
        $q = urldecode($request->q);
        $itemsPerPage = $request->itemsPerPage;
        $query = Product::where('p_type', 'like', '%' . $q . '%')
            ->orWhere('p_name', 'like', '%' . $q . '%')
            ->orWhere('p_part_no', 'like', '%' . $q . '%')
            ->orWhere('p_spec', 'like', '%' . $q . '%')
            ->orWhere('p_currency', 'like', '%' . $q . '%')
            ->orWhere('p_size', 'like', '%' . $q . '%')
            ->orWhere('p_weight', 'like', '%' . $q . '%')
            ->orWhere('p_note', 'like', '%' . $q . '%');

        if ($sortBy = $request->sortBy) {
            $sortDesc = $request->sortDesc;
            $query = $query->orderBy($sortBy, $sortDesc);
        }

        if ($id = $request->id) {
            $query = $query->where('id', '>=', $id);
        }

        $products = $query->paginate($itemsPerPage);

        return response()->json($products);
    }
}
