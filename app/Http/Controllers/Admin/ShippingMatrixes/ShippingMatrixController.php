<?php

namespace App\Http\Controllers\Admin\ShippingMatrixes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\ShippingMatrixes\ShippingMatrixRequest;

use App\Models\ShippingMatrixes\ShippingMatrix;

use DB;

class ShippingMatrixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.shipping-matrixes.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.shipping-matrixes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShippingMatrixRequest $request)
    {
        /** Start transaction */
        DB::beginTransaction();

            $item = ShippingMatrix::store($request);

        /** End transaction */
        DB::commit();

        $message = "You have been successfully created a new shipping matrix";
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
        $item = ShippingMatrix::withTrashed()->findOrFail($id);

        return view('admin.shipping-matrixes.show', [
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
    public function update(ShippingMatrixRequest $request, $id)
    {
        $item = ShippingMatrix::withTrashed()->findOrFail($id);

        /** Start transaction */
        DB::beginTransaction();

            $item = ShippingMatrix::store($request, $item);

        /** End transaction */
        DB::commit();

        $message = "You have been successfully updated a shipping matrix";
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
        $item = ShippingMatrix::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived a shipping matrix",
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
        $item = ShippingMatrix::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored a shipping matrix",
        ]);
    }
}
