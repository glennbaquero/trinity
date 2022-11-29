<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\ConsultationChats\ConsultationChat;

use DB;

trait ChatTrait {

    /*
    |--------------------------------------------------------------------------
    | Methods
    |--------------------------------------------------------------------------
    */
   
    /**
     * Fetch chats
     * 
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
   	public function fetch(Request $request)
   	{
        $filteredConversations = ConsultationChat::filter($request->consultation_id);
   		return $filteredConversations;
   	}

    /**
     * Store resource from storage
     * 
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function storeMessage(Request $request)
    {
        /** Start transaction */
        DB::beginTransaction();

            ConsultationChat::store($request);

        /** End transaction */
        DB::commit();

        return response()->json([
            'message' => 'Message sent'
        ]);

    }

}

