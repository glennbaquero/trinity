<?php

namespace App\Http\Controllers\API\Care\Doctors;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\Doctor\ScannedNotification;
use App\Http\Controllers\API\Care\Doctors\DoctorFetchController;

use App\Models\Users\Doctor;

use App\Services\PushServiceDoctor;
use Illuminate\Validation\ValidationException;

use DB;

class DoctorController extends Controller
{
    public function scanQr(Request $request)
    {
        $user = $request->user();
        $qr = preg_replace('/\"/', '', $request->qr_id);
        $scannedDoctor = Doctor::where('qr_id', $qr)->first();

        if (!$request->qr_id) {
            throw ValidationException::withMessages([
                'qr_id' => ['Please enter a QR ID']
            ]);
        }

        if (!$scannedDoctor) {
            throw ValidationException::withMessages([
                'qr_id' => ['Doctor does not exist']
            ]);
        }

        if ($user->doctors->where('qr_id', $qr)->first()) {
            throw ValidationException::withMessages([
                'qr_id' => ['Doctor with scanned QR is already linked to you']
            ]);
        }

        DB::beginTransaction();
            $existingDoctor = $user->doctors()->where('specialization_id', $scannedDoctor->specialization_id)->first();

            if ($existingDoctor) {
                $user->doctors()->detach($existingDoctor->id);
            }
            
            $scannedDoctor->patients()->attach($user);
        DB::commit();

        $push = new PushServiceDoctor('A new patient is added you', $user->renderFullName().' is added you as a personal '.$scannedDoctor->specialization->name);
        $push->pushToOne($scannedDoctor);

        $scannedDoctor->notify(new ScannedNotification($user, $scannedDoctor));

        return response()->json([
            'message' => 'New '. $scannedDoctor->specialization->name. ' is added!',
            'specialization' => $scannedDoctor->specialization->name,
            'doctor' => $scannedDoctor,
            'response' => 200
        ]);
    }

    public function validateQr(Request $request)
    {
        $request->qr_id = str_replace('"', '', $request->qr_id);

        if (!$request->user()->doctors->where('qr_id', $request->qr_id)->first()) {
            throw ValidationException::withMessages([
                'qr' => ['Doctor with scanned QR is not linked to you']
            ]);
        }

        $request->user()->products()->attach($request->product_id);
        
        return response()->json([
            'message' => 'Validation complete'
        ]);
    }

    public function doctorList(Request $request) 
    {
        
        $doctors = new Doctor;
        
        if($request->filled('search')) {
            $doctors = $doctors->whereRaw("concat(first_name, ' ', last_name) like '%".$request->search."%' ");
        }

        if($request->filled('specialization_id')) {
            if($request->specialization_id != 0) {
                $doctors = $doctors->where('specialization_id',$request->specialization_id);
            }
        }

        // Get 2 doctors only
        $doctors = $doctors->where('consultation_fee','>',0)->inRandomOrder()->take(2)->get();

        $doctors = collect($doctors)->map(function($doctor) {
            $doctor->ratings = $doctor->computeRatings();
            return $doctor;
        });

        return response()->json([
            'doctors' => $doctors
        ]);

    }   
}
