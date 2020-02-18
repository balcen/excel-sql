<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\ExcelFileUpload;
use App\Repositories\Products\ProductSearch;
use App\Repositories\Products\ProductResource;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()
            ->json(ProductResource::index($request));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        return response()
            ->json(ProductResource::store($request));

//        $image = $request->file('image');
//         if(isset($image)) {
//             $image = $request->file('image');
//             $imageName = '00_Image_'.uniqid().'.'.$image->getClientOriginalExtension();
//             $imageContent = file_get_contents($image);
//             $product['p_image'] = $imageName;
//         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        return response()
            ->json(ProductResource::update($request, $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        return response()
            ->json(ProductResource::destroy($id));
//        $image = $product->value('p_image');
//        if($image = $product->value('p_image')) {
//            Storage::disk('public')->delete($product->value('p_image'));
//        }
    }

    public function deleteAll(Request $request)
    {
        return response()
            ->json(ProductResource::batchDelete($request));
    }

    public function upload(Request $request)
    {
        return response()
            ->json(['id' => ProductResource::upload($request)]);
    }

    public function searchAll(Request $request)
    {
        return response()
            ->json(ProductSearch::apply($request));
    }
}
