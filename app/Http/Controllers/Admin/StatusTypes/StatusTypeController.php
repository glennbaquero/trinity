<?php

namespace App\Http\Controllers\Admin\StatusTypes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\StatusTypes\StatusTypeStorePost;

use App\Models\StatusTypes\StatusType;
use App\Models\Invoices\Invoice;

class StatusTypeController extends Controller
{

    public function get()
    {
        $statusTypes = StatusType::get();
        $statuses = $statusTypes->map(function($status) {
            return [
                'name' => $status->name,
                'count' => Invoice::where('status_id', $status->id)->count()
            ];
        });

        return response()->json(compact('statuses'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.statustypes.index');
    }


    /**
     * Re-ordering status types
     * 
     * @param  Request $request
     */
    public function reOrder(Request $request)
    {

        foreach ($request->types as $key => $type) {

            $status = StatusType::find($type['id']);

            if($status) {
                $status->update(['order' => $key ]);
            }

        }

        return response()->json([
            'message' => 'Successfully updated the order of status types',
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $actionTypes = StatusType::getActionTypes(); 
        return view('admin.statustypes.create', [
            'actionTypes' => $actionTypes,
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatusTypeStorePost $request)
    {
        
        /** Start transaction */
        \DB::beginTransaction();

            /** Store statustype */
            $statustype = StatusType::store($request);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully created {$statustype->name}";
        $redirect = $statustype->renderShowUrl();

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
        $status = StatusType::withTrashed()->find($id);
        $actionTypes = StatusType::getActionTypes(); 
        
        return view('admin.statustypes.show', [
            'actionTypes' => $actionTypes,
            'status' => $status,
        ]);        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StatusTypeStorePost $request, $id)
    {
        $status = StatusType::withTrashed()->find($id);
        $message = "You have successfully created {$status->name}";

        /** Start transaction */
        \DB::beginTransaction();

            /** Store statustype */
            $statustype = StatusType::store($request, $status);

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => $message,
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
        $status = StatusType::find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Soft delete status */
            $status->archive();

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => "You have successfully archived {$status->name}",
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

        $status = StatusType::onlyTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            $status = $status->checkOrderNumber();
            
            /** Recover/Restore status */
            $status->unarchive();

        /** End transaction */
        \DB::commit();        

        return response()->json([
            'message' => "You have successfully restored {$status->name}",
        ]);

    }
}
