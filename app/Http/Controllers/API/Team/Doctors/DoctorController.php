<?php

namespace App\Http\Controllers\API\Team\Doctors;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\Doctors\DoctorRequest;
use App\Http\Controllers\Controller;

use App\Models\Users\Doctor;

class DoctorController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DoctorRequest $request)
    {

        $vars = $request->except(['']);
        $vars['medical_representative_id'] = $request->user()->id;
        $vars['password'] = uniqid();
        $vars['mobile_number'] = $request->filled('mobile_number') ? $request->mobile_number : '# not attach';

        /** Start transaction */
        \DB::beginTransaction();

            /** Doctor create */
            $doctor = Doctor::create($vars);

        /** End transaction */
        \DB::commit();

        /** Send email verification */
        $broker = $doctor->broker();
        $broker->sendResetLink($request->only('email'));

        return response()->json([
            'status' => 'success',
            'doctor' => $doctor
        ]);
    }

}
