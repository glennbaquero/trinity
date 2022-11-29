<?php

namespace App\Http\Controllers\API\Team\MedRepLocationLogs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\MedRepLocationLogs\MedRepLocationLog;

class MedRepLocationLogController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $vars = $request->except([]);
        $vars['medical_representative_id'] = $request->user()->id; 
        $log = $request->user()->locationLog;
        
        if($log) {
            $log->update($vars);
        } else {
            $logs = MedRepLocationLog::create($vars);
        }

        return response()->json([
            'log' => $log
        ]);
    }


}
