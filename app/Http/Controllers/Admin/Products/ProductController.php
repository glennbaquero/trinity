<?php

namespace App\Http\Controllers\Admin\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Products\ProductStorePost;

use App\Imports\Products\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Products\Product;
use App\Models\Specializations\Specialization;
use App\Models\Products\ProductParent;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $specializations = Specialization::all();

        return view('admin.products.index',[
            'specializations' => $specializations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null, $name = null)
    {
        $product = null;
        $variant = false;
        if($id) {
            $product = Product::find($id);
            $variant = true;
        }

        return view('admin.products.create', [
            'product' => $product,
            'variant' => $variant
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStorePost $request, $id = null)
    {
        
        /** Start transaction */
        \DB::beginTransaction();

            /** Store product */
            $product = Product::store($request);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully created {$product->name}";
        $redirect = $product->renderShowUrl();

        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::withTrashed()->find($id);

        return view('admin.products.show', [
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductStorePost $request, $id)
    {

        $product = Product::withTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Update product */
            $product = Product::store($request, $product);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully update {$product->name}";

        return response()->json([
            'message' => $message,
        ]);

    }

    public function variants($id)
    {
        $product = ProductParent::withTrashed()->find($id);

        return view('admin.products.variants', [
            'product' => $product,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $product = Product::find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Soft delete product */
            $product->archive();

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => "You have successfully archived {$product->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {

        $product = Product::onlyTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Recover/Restore product */
            $product->unarchive();

        /** End transaction */
        \DB::commit();        

        return response()->json([
            'message' => "You have successfully restored {$product->name}",
        ]);

    }

    /**
     * Batch import of product index
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function upload()
    {
        return view('admin.products.import');
    }

    /**
     * Batch import of product
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function uploadProducts(Request $request)
    {
        $result = Excel::import(new ProductsImport($request), $request->file('file'));

        return response()->json([
            'title' => 'Success',
            'message' => 'You have successfully uploaded a manifest.',
        ]);
    }
}
