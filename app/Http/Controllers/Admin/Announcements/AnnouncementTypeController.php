<?php

namespace App\Http\Controllers\Admin\Announcements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Announcements\AnnouncementType;

class AnnouncementTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.announcement-types.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.announcement-types.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        /** Start transaction */
        \DB::beginTransaction();

            /** Store product */
            $item = AnnouncementType::store($request);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully created {$item->name}";
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
        $item = AnnouncementType::withTrashed()->find($id);

        return view('admin.announcement-types.show', [
            'item' => $item
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
    public function update(Request $request, $id)
    {

        $item = AnnouncementType::withTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Update product */
            $item = AnnouncementType::store($request, $item);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully update {$item->name}";

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
        $item = AnnouncementType::find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Soft delete product */
            $item->archive();

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => "You have successfully archived {$item->name}",
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

        $item = AnnouncementType::onlyTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Recover/Restore product */
            $item->unarchive();

        /** End transaction */
        \DB::commit();        

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);

    }
}
