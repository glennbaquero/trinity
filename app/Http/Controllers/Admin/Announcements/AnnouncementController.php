<?php

namespace App\Http\Controllers\Admin\Announcements;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Requests\Admin\Announcements\AnnouncementStoreRequest;

use App\Models\Announcements\Announcement;
use App\Models\Users\Doctor;
use App\Models\Users\User;
use App\Models\Users\MedicalRepresentative;

use App\Services\PushService;
use App\Services\PushServiceDoctor;
// use App\Services\PushServiceTeam;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.announcements.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\AnnouncementStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnnouncementStoreRequest $request)
    {
        
        /** Start transaction */
        \DB::beginTransaction();

            /** Store product */
            $item = Announcement::store($request);

        /** End transaction */
        \DB::commit();

        // $item->notifyUsers();

        $message = "You have successfully created {$item->title}";
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
        $item = Announcement::withTrashed()->find($id);

        return view('admin.announcements.show', [
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
     * @param  \Illuminate\Http\AnnouncementStoreRequest $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AnnouncementStoreRequest $request, $id)
    {

        $item = Announcement::withTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Update product */
            $item = Announcement::store($request, $item);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully update {$item->title}";

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
        $item = Announcement::find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Soft delete product */
            $item->archive();

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => "You have successfully archived {$item->title}",
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

        $item = Announcement::onlyTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Recover/Restore product */
            $item->unarchive();

        /** End transaction */
        \DB::commit();        

        return response()->json([
            'message' => "You have successfully restored {$item->title}",
        ]);

    }
}
