<?php

namespace App\Http\Controllers\Admin\Sponsorships;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Rewards\Sponsorship;

class SponsorshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.sponsorships.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sponsorships.create');
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

            /** Store specialization */
            $sponsorship = Sponsorship::store($request);

        /** End transaction */
        \DB::commit();

        $message = "You have successfully created {$sponsorship->name}";
        $redirect = $sponsorship->renderShowUrl();

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
        $sponsorship = Sponsorship::withTrashed()->find($id);

        return view('admin.sponsorships.show', [
            'sponsorship' => $sponsorship
        ]);
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

        $sponsorship = Sponsorship::withTrashed()->find($id);
        $message = "You have successfully created {$sponsorship->name}";

        /** Start transaction */
        \DB::beginTransaction();

            /** Store Sponsorship */
            $sponsorship = Sponsorship::store($request, $sponsorship);

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
        $sponsorship = Sponsorship::find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Soft delete sponsorship */
            $sponsorship->archive();

        /** End transaction */
        \DB::commit();

        return response()->json([
            'message' => "You have successfully archived {$sponsorship->name}",
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

        $sponsorship = Sponsorship::onlyTrashed()->find($id);

        /** Start transaction */
        \DB::beginTransaction();

            /** Recover/Restore sponsorship */
            $sponsorship->unarchive();

        /** End transaction */
        \DB::commit();        

        return response()->json([
            'message' => "You have successfully restored {$sponsorship->name}",
        ]);

    }
}
