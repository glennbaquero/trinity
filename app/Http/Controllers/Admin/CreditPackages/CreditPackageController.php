<?php

namespace App\Http\Controllers\Admin\CreditPackages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\CreditPackages\CreditPackageStoreRequest;

use App\Models\CreditPackages\CreditPackage;

use DB;

class CreditPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.credit-packages.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.credit-packages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreditPackageStoreRequest $request)
    {

        /** Start transaction */
        DB::beginTransaction();

            /** Store Credit Package Record */
            $item = CreditPackage::store($request);


        /** End transaction */
        DB::commit();

        $message = "You have successfully created {$item->id}";
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
        $item = CreditPackage::withTrashed()->findOrFail($id);
        
        return view('admin.credit-packages.show', [
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreditPackageStoreRequest $request, $id)
    {
        $item = CreditPackage::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->name}";

        DB::beginTransaction();
            $item = CreditPackage::store($request, $item);
        DB::commit();
        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CreditPackage  $CreditPackages
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = CreditPackage::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived credit package {$item->name}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\CreditPackage  $CreditPackage
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = CreditPackage::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored credit package {$item->name}",
        ]);
    }
}
