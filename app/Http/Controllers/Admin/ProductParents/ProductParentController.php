<?php

namespace App\Http\Controllers\Admin\ProductParents;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Products\ProductParentRequest;

use App\Models\Products\ProductParent;

use DB;

class ProductParentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.product-parents.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product-parents.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductParentRequest $request)
    {
        /** Start transaction */
        DB::beginTransaction();

            $item = ProductParent::store($request);

        /** End transaction */
        DB::commit();

        $message = "You have been successfully created {$item->name}";
        $redirect = $item->renderShowUrl();

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
        $item = ProductParent::withTrashed()->findOrFail($id);

        return view('admin.product-parents.show', [
            'item' => $item
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductParentRequest $request, $id)
    {
        $item = ProductParent::withTrashed()->findOrFail($id);

        /** Start transaction */
        DB::beginTransaction();

            $item = ProductParent::store($request, $item);

        /** End transaction */
        DB::commit();

        $message = "You have been successfully updated {$item->name}";
        $redirect = $item->renderShowUrl();
        
        return response()->json([
            'message' => $message,
            'redirect' => $redirect,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Article  $articles
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = ProductParent::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Article  $car
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = ProductParent::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }
}
