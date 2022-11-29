<?php

namespace App\Http\Controllers\API\Doctor\ConsultationChats;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\ConsultationChats\ConsultationChat;
use App\Models\Consultations\Consultation;

use DB;

class ConsultationChatController extends Controller
{


    /**
     * Fetch chats
     * 
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        $filteredConversations = ConsultationChat::filter($request->consultation_id);
        $consultation = Consultation::find($request->consultation_id);

        if($consultation->schedule) {
            $consultation->start_time = $consultation->start_time .' '. $consultation->schedule->type;            
        }

        return response()->json([
            'conversation' => $filteredConversations,
            'consultation' => $consultation,
            'consultationStatus' => $consultation->renderStatus(),            
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

        /** Start transaction */
        DB::beginTransaction();

            /** Store ConsultationChat Record */
            $item = ConsultationChat::store($request);

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => 'Message sent'
        ]);

    }
}
