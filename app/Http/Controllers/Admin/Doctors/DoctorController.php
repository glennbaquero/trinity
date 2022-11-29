<?php

namespace App\Http\Controllers\Admin\Doctors;

use App\Models\Users\Doctor;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Doctors\DoctorRequest;
use App\Http\Controllers\Controller;

use App\Notifications\Doctor\ApproveDoctor;
use App\Notifications\Doctor\RejectDoctor;
use App\Notifications\Admin\CreditsUpdate\CreditsUpdateNotification;

use App\Models\Users\MedicalRepresentative;

use Notification;

class DoctorController extends Controller
{

    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Doctors\DoctorMiddleware', 
            ['only' => ['index', 'create', 'update', 'archive', 'restore', 'reject', 'approve']]
        );
    }

    public function get()
    {
        $doctorsCount = Doctor::count();
        return response()->json(compact('doctorsCount'));
    }
    
    /**
    * Store province in DB
    *
    * @return Illuminate\Http\Response
    */
	public function index()
	{
        $medreps = MedicalRepresentative::get();
		return view('admin.doctors.index', compact('medreps'));
	}


    /**
    * View a specific doctor
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
	public function show(int $id)
    {
        $doctor = Doctor::withTrashed()->findOrFail($id);

        return view('admin.doctors.show', compact('doctor'));
    }


    /**
    * Show add doctor form
    *
    * @return Illuminate\Http\Response
    */
	public function create()
	{
		return view('admin.doctors.create');
	}


    /**
    * Store a doctor to DB
    *
    * @param App\Http\Requests\Admin\Doctors\DoctorRequest $request
    * @return Illuminate\Http\Response
    */
	public function store(DoctorRequest $request)
	{
		\DB::beginTransaction();
		$item = Doctor::store($request);
        $item->update(['status' => 1]);
		\DB::commit();

		$message = "You have successfully created Doctor #{$item->id}!";
		$redirect = $item->renderShowUrl();

		return response()->json(compact('message', 'redirect'));
	}


    /**
    * Update a specific doctor
    *
    * @param App\Http\Requests\Admin\Doctors\DoctorRequest $request
    * @return Illuminate\Http\Response
    */
	public function update(DoctorRequest $request, int $id)
    {
        $item = Doctor::withTrashed()->findOrFail($id);
        $message = "You have successfully updated Doctor #{$item->id}";

        $item = Doctor::store($request, $item);

        return response()->json(compact('message'));
    }


    /**
    * Archive a specific doctor
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function archive(int $id)
    {
        $item = Doctor::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived Doctor #{$item->id}",
        ]);
    }

    /**
    * Restore a specific doctor
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function restore(int $id)
    {
        $item = Doctor::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored Doctor #{$item->id}",
        ]);
    }

    /**
    * Approve a specific call
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function approve(int $id)
    {
        $doc = Doctor::find($id);
        $doc->update(['status' => 1]);

        Notification::send($doc, new ApproveDoctor($doc));

        return response()->json([
            'message' => "You have successfully approved Doctor #{$id}",
            'redirectUrl' => route('admin.doctors.index')
        ]);
    }


    /**
    * Reject a specific call
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function reject(int $id)
    {
        $doc = Doctor::find($id);
        $doc->update(['status' => 2]);

        Notification::send($doc, new RejectDoctor($doc));

        return response()->json([
            'message' => "You have successfully rejected Doctor #{$id}",
            'redirectUrl' => route('admin.doctors.index')
        ]);
    }

    /**
     * Send password reset
     * 
     * @param  int $id
     */
    public function sendPasswordReset($id)
    {
        $doctor = Doctor::find($id);
        $broker = $doctor->broker();
        $broker->sendResetLink(['email' => $doctor->email]);

        return response()->json([
            'message' => 'Password reset successfully sent',
        ]);
    }

    /**
     * Download doctor QR code
     * 
     * @param  int $id
     */
    public function downloadQR($id)
    {
        $doctor = Doctor::find($id);

        $path = \Storage::url($doctor->qr_code_path);
        $filename = 'dr_' . str_replace(' ', '_', strtolower($doctor->fullname)).'.png';

        return response()->download(public_path($path), $filename);

    }

    /**
     * Manage credits form
     * 
     * @param  int $id
     */
    public function manageCreditsForm($id)
    {
        $item = Doctor::find($id);

        return view('admin.manage-credits.show', [
            'item' => $item,
            'submitUrl' => route('admin.doctors.update-credits', $item->id)            
        ]);
    }

    /**
     * Manage credits form
     * 
     * @param  int $id
     */
    public function updateCredits(Request $request, $id)
    {
        $item = Doctor::find($id);
        
        if($request->type == 1) {
            $item->processCredit($request->value);
        } else {
            $item->processCredit('-' . $request->value);
        }

        $item->notify(new CreditsUpdateNotification($request->message));

        return response()->json([
            'message' => "User credits has been updated",
        ]);

    }

}
