<?php

namespace App\Http\Controllers\API\Team\Resources;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Http\Controllers\API\Team\Calls\CallFetchController;
use App\Http\Controllers\API\Team\Doctors\DoctorFetchController;

use App\Models\MedRepTargets\MedRepTarget;

class HomeController extends Controller
{

	public function reports(Request $request)
	{
        $reports = MedRepTarget::formatReport($request, $request->user()->id);
 
        return response()->json([
        	'reports' => $reports
        ]);		
	}
    
}
