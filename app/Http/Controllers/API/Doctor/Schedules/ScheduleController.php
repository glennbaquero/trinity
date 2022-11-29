<?php

namespace App\Http\Controllers\API\Doctor\Schedules;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Schedules\Schedule;

use DB;

class ScheduleController extends Controller
{
    	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /** Start transaction */
        DB::beginTransaction();

            /** Store Schedule Record */
            $item = Schedule::store($request);


        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => "The schedule has been successfully saved.",
        ]);
    }

}
