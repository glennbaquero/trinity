<?php

namespace App\Http\Controllers\Admin\ShippingFees;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\ShippinFees\ExpressStoreRequest;
use App\Http\Controllers\Controller;

use App\Models\ShippingMethod\Express;
use App\Models\Cities\City;

use DB;
class ExpressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.expresses.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities = City::all();
        $data = [];
        foreach ($cities as $city) {
            if(!$city->express)
                array_push($data, $city);
        }

        return view('admin.expresses.create', [
            'cities' => collect($data)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpressStoreRequest $request)
    {
        DB::beginTransaction();
            $item = Express::store($request);
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
        $cities = City::all();
        $data = [];
        foreach ($cities as $city) {
            if(!$city->express) {
                array_push($data, $city);
            }
        }

        $item = Express::withTrashed()->findOrFail($id);
        array_push($data, $item->city);
        
        return view('admin.expresses.show', [
            'item' => $item,
            'cities' => collect($data)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(ExpressStoreRequest $request, $id)
    {
        $item = Express::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->title}";

        $item = Express::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Express  $expresses
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Express::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived Express #{$item->id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Express  $express
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Express::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored Express #{$item->id}",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
