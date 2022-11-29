<?php

namespace App\Http\Controllers\Admin\MedicalRepresentatives;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\MedicalRepresentatives\MedicalRepresentativeStoreRequest;
use App\Http\Controllers\Controller;

use App\Models\Users\MedicalRepresentative;
use App\Models\MedRepTargets\MedRepTarget;
use App\Models\MedRepLocationLogs\MedRepLocationLog;


use DB;

class MedicalRepresentativeController extends Controller
{

    public function get()
    {
        $medRepsCount = MedicalRepresentative::count();
        return response()->json(compact('medRepsCount'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.medreps.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.medreps.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MedicalRepresentativeStoreRequest $request)
    {
        DB::beginTransaction();
        $item = MedicalRepresentative::store($request);
        DB::commit();
        $message = "You have successfully created {$item->fullname}";
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
        $item = MedicalRepresentative::withTrashed()->findOrFail($id);
        $months = MedRepTarget::generateMonths();
        $types = MedRepTarget::generateTargetType();
        $years = MedRepTarget::generateYears();
        
        return view('admin.medreps.show', [
            'item' => $item,
            'months' => $months,
            'types' => $types,
            'years' => $years,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MedicalRepresentativeStoreRequest $request, $id)
    {
        $item = MedicalRepresentative::withTrashed()->findOrFail($id);
        $message = "You have successfully updated {$item->fullname}";

        $item = MedicalRepresentative::store($request, $item);

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
        $item = MedicalRepresentative::withTrashed()->findOrFail($id);
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
        $item = MedicalRepresentative::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored {$item->name}",
        ]);
    }

    /**
     * Fetch incentives and sales reports
     * 
     * @param  Request $request
     * @param  int  $id
     */
    public function reports(Request $request, $id)
    {

        $reports = MedRepTarget::formatReport($request, $id);

        return response()->json([
            'reports' => $reports,
        ]);
    }

    public function locationLogs(Request $request)
    {
        $logs = MedRepLocationLog::with('medrep')->get();

        return view('admin.medreps.location-logs', [
            'logs' => $logs,
        ]);
    }
}
