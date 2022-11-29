<?php

namespace App\Http\Controllers\Admin\TargetCalls;

use App\Http\Requests\Admin\TargetCalls\TargetCallStoreRequest;
use App\Http\Controllers\Controller;

use App\Models\Calls\TargetCall;
use App\Models\Users\MedicalRepresentative;

use DB;

class TargetCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $months = TargetCall::generateMonths()->all();

        return view('admin.target-calls.index', [
            'months' => json_encode($months)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $medreps = MedicalRepresentative::get();
        $months = TargetCall::generateMonths();

        return view('admin.target-calls.create', [
            'medreps' => collect($medreps),
            'months' => $months
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TargetCallStoreRequest $request)
    {

        if(TargetCall::alreadyExists($request)) {
            return response()->json([
                'errors' => ['target' => ['Target entry already exists']],
                'message' => 'Invalid entry']
                , 422);
        }        


        DB::beginTransaction();
            $item = TargetCall::store($request);
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
        $medreps = MedicalRepresentative::get();
        $item = TargetCall::withTrashed()->findOrFail($id);
        $months = TargetCall::generateMonths();

        return view('admin.target-calls.show', [
            'medreps' => collect($medreps),
            'item' => $item,
            'months' => $months            
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
    public function update(TargetCallStoreRequest $request, $id)
    {

        $item = TargetCall::withTrashed()->findOrFail($id);

        if(TargetCall::alreadyExists($request, $item->id)) {
            return response()->json([
                'errors' => ['target' => ['Target entry already exists']],
                'message' => 'Invalid entry']
                , 422);
        }


        $message = "You have successfully updated a target call";

        $item = TargetCall::store($request, $item);

        return response()->json([
            'message' => $message,
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
        $item = TargetCall::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived {$item->title}",
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
        $item = TargetCall::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
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
