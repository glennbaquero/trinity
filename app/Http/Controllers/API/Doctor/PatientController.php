<?php

namespace App\Http\Controllers\API\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    /**
     * Fetch all doctor's linked patients
     * 
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        $doctor = request()->user();

        $data = $doctor->patients();
        
        if(request()->search) {
            $data->where('first_name', 'like', '%' . request()->search . '%')
                 ->orWhere('last_name', 'like', '%' . request()->search . '%');
        }

        $data->orderby('first_name', request()->sort);
        
        return response()->json([
            'data' => $data->paginate(10),
        ]);
    }
}
