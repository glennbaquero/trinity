<?php

namespace App\Http\Controllers\Admin\Specializations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Specializations\SpecializationsStorePost;

use App\Models\Specializations\Specialization;

class SpecializationController extends Controller
{

    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Specializations\SpecializationMiddleware', 
            ['only' => ['index', 'create', 'update', 'archive', 'restore']]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.specializations.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.specializations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpecializationsStorePost $request)
    {
        /** Start transaction */
        \DB::beginTransaction();

            /** Store specialization */
            $specialization = Specialization::store($request);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully created {$specialization->name}";
        $redirect = $specialization->renderShowUrl();

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
        $specialization = Specialization::withTrashed()->find($id);

        return view('admin.specializations.show', [
            'specialization' => $specialization
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SpecializationsStorePost $request, $id)
    {

        $specialization = Specialization::withTrashed()->find($id);
        $message = "You have successfully created {$specialization->name}";

        /** Start transaction */
        \DB::beginTransaction();

            /** Store specialization */
            $specialization = Specialization::store($request, $specialization);

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
        $specialization = Specialization::find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Soft delete specialization */
            $specialization->archive();

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => "You have successfully archived {$specialization->name}",
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

        $specialization = Specialization::onlyTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Recover/Restore specialization */
            $specialization->unarchive();

        /** End transaction */
        \DB::commit();        

        return response()->json([
            'message' => "You have successfully restored {$specialization->name}",
        ]);

    }

    public function reOrder(Request $request)
    {

        foreach ($request->items as $key => $item) {

            $specialization = Specialization::find($item['id']);

            if($specialization) {
                $specialization->update(['order' => $key ]);
            }

        }

        return response()->json([
            'message' => 'Successfully updated the order of specializations',
        ]);

    }
}
