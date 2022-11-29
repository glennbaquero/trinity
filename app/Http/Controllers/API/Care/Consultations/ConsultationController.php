<?php

namespace App\Http\Controllers\API\Care\Consultations;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Consultations\Consultation;

use DB;

class ConsultationController extends Controller
{

    public function fetchConsultation(Request $request) 
    {
        if($request->params == 'consultation_number') {
            $consultation = Consultation::where('consultation_number', $request->consultation_number)->first();
        } else {
            $consultation = Consultation::find($request->id);
        }
            $consultation->doctor = $consultation->doctor;
            $consultation->doctor_name = $consultation->doctor->renderName();
            $consultation->doctor_image = $consultation->doctor->getFullImageAttribute();
            $consultation->schedule_type = $consultation->schedule ? $consultation->schedule->type : null;        
            $consultation->status = $consultation->renderStatus();            

        return response()->json([ 
            'consultation' => $consultation
        ]);
    }

	/**
	 * Fetch resources from storage
	 * 
	 * @return Illuminate\Http\Response
	 */
	public function fetch(Request $request)
	{

        $user = $request->user();

        $pending_consultations = Consultation::fetch('user_id', [Consultation::PENDING], 'greater-to-now', null);
        $approved_consultations = Consultation::fetch('user_id', [Consultation::APPROVED], 'greater-to-now', null);        
        $credits = (int) $user->countCredits();

		return response()->json([
			'approved_consultations' => $approved_consultations,
            'pending_consultations' => $pending_consultations,
            'credits' => $credits
		]);
	}

    /**
     * Fetch history
     * 
     * @return Illuminate\Http\Response
     */
    public function fetchHistory(Request $request)
    {
        if($request->status == 'completed') {
            if($request->paginate == 'no-pagination') {
                $consultation_history = Consultation::fetch('user_id', [Consultation::COMPLETED],null,null);
            } else {
                $consultation_history = Consultation::fetch('user_id', [Consultation::COMPLETED],null,null,true,$request->page);
            }
        } else {
            $consultation_history = Consultation::fetch('user_id', [Consultation::COMPLETED, Consultation::APPROVED, Consultation::DISAPPROVED, Consultation::CANCELLED, Consultation::REFUNDED],null,null,true,$request->page);            
        }

        return response()->json([
            'consultation_history' => $consultation_history,
        ]);        
    }

    /**
     * Check pending consultation
     * 
     * @param  Request $request
     */
    public function checkPending(Request $request)
    {
        $consultation = null;

        if(!Consultation::hasSufficientCredits($request->doctor_id, 0, '')) {
            return response()->json([
                'canbook' => 'unavailable',
                'title' => 'Insufficient Credits',
                'message' => 'Insufficient credits. Please add credits to your account',                
            ]);
        }

        if($consultation = Consultation::checkPending($request->doctor_id)) {
            return response()->json([
                'exists' => true,
                'consultation' => $consultation,
                'doctor' => $consultation ? $consultation->doctor : '' ,                
            ]);
        }
        return response()->json([
            'exists' => false,
            'consultation' => $consultation,
            'doctor' => '',
        ]);
    }

    /**
     * Store chat request
     * 
     * @param  Request $request
     */
    public function chatRequest(Request $request)
    {

        /** Start transaction */
        DB::beginTransaction();

            /** Store Consultation Record */
            $item = Consultation::store($request);

        /** End transaction */
        DB::commit();

        return response()->json([
            'title' => 'Chat Request Sent',
            'message' => "Chat request has been sent",
            'consultation' => $item,
            'doctor' => $item->doctor,            
        ]);

    }

	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if(Consultation::checkBooking($request->schedule_id)) {
            return response()->json([
                'title' => "Booking already exists",
                'message' => "You have been already booked a consultation in this schedule",
            ]);
        } else if(Consultation::checkSchedule($request->schedule_id)) {
            return response()->json([
                'title' => "Failed to book schedule",
                'message' => "This schedule is unavailable right now",
            ]);
        } else {

            /** Start transaction */
            DB::beginTransaction();

                /** Store Consultation Record */
                $item = Consultation::store($request);

            /** End transaction */
            DB::commit();

            return response()->json([
                'title' => 'Booking Request Sent',
                'message' => "Your credits will be deducted once the doctor have confirmed the booking",
            ]);

        }

    }

    /**
     * Set consultation to completed
     * 
     * @param  Request $request
     */
    public function completed(Request $request)
    {   
        $item = Consultation::find($request->consultation_id);
        
        /** Start transaction */
        DB::beginTransaction();        

            /** Set status to COMPLETED */
            $item->setStatus(Consultation::COMPLETED);

        /** End transaction */
        DB::commit();

        return response()->json([
            'title' => 'Consultation completed',
            'message' => "Consultation has been automatically updated to completed",
        ]);
    }


	/**
     * Cancel specified resource from storage
     * 
     * @param  int $id
     */
    public function cancel(Request $request)
    {
        $item = Consultation::findOrFail($request->id);       
        
        /** Start transaction */
        DB::beginTransaction();

            $item->updateStatus();

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => "You have successfully cancelled the consultation request",
        ]);

    }

    /**
     * Send the doctor a notification when the user has entered the chat page
     * 
     * @param  Request $request
     */
    public function sendNotification(Request $request)
    {
        $consultation = Consultation::find($request->consultation_id);
        $consultation->sendArrivalPushNotif($request->isUser);  
    }

}
