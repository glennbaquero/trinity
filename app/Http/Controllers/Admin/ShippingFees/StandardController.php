<?php

namespace App\Http\Controllers\Admin\ShippingFees;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ShippinFees\StandardStoreRequest;

use App\Models\ShippingMethod\Standard;
use App\Models\Provinces\Province;

use DB;

class StandardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.standards.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provinces = Province::all();
        $data = [];
        foreach ($provinces as $province) {
            if(!$province->standard)
                array_push($data, $province);
        }

        return view('admin.standards.create', [
        	'provinces' => collect($data)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StandardStoreRequest $request)
    {
        DB::beginTransaction();
        	$item = Standard::store($request);
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
        $provinces = Province::all();
        $data = [];
        foreach ($provinces as $province) {
            if(!$province->standard) {
                array_push($data, $province);
            }
        }

        $item = Standard::withTrashed()->findOrFail($id);
        array_push($data, $item->province);
        
        return view('admin.standards.show', [
            'item' => $item,
            'provinces' => collect($data)
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
    public function update(StandardStoreRequest $request, $id)
    {
        $item = Standard::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->title}";

        $item = Standard::store($request, $item);

        return response()->json([
            'message' => $message,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Standard  $standards
     * @return \Illuminate\Http\Response
     */
    public function archive($id)
    {
        $item = Standard::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived Standard #{$item->id}",
        ]);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  \App\Standard  $car
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $item = Standard::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored Standard #{$item->id}",
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
