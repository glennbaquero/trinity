<?php

namespace App\Http\Controllers\API\Doctor\Sessions;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users\User;
use App\Models\Sessions\VideoCallSession;

use OpenTok\OpenTok;
use OpenTok\MediaMode;
use OpenTok\ArchiveMode;

use App\Services\PushService;

use DB;
use Carbon\Carbon;

class VideoCallSessionController extends Controller
{
    /**
     * Store specified resource from storage
     * 
     * @param  Array $request
     * @return  object $item
     */
    public function store(Request $request)
    {

    	DB::beginTransaction();
    		// initialze api using api key/secret
    	    $openTokAPI = new OpenTok(env('OPENTOK_API_KEY'), env('OPENTOK_API_SECRET'));

    	    // Create a session that attempts to use peer-to-peer streaming:
    	    $session = $openTokAPI->createSession();

    	    // A session that uses the OpenTok Media Router, which is required for archiving:
    	    $session = $openTokAPI->createSession(array( 'mediaMode' => MediaMode::ROUTED ));

    	    // A session with a location hint:
    	    $session = $openTokAPI->createSession(array( 'location' => '12.34.56.78' ));

    	    // An automatically archived session:
    	    $sessionOptions = array(
    	        'archiveMode' => ArchiveMode::ALWAYS,
    	        'mediaMode' => MediaMode::ROUTED
    	    );
    	    $session = $openTokAPI->createSession($sessionOptions);

    	    // Store this sessionId in the database for later use
    	    $sessionId = $session->getSessionId();

           	// now, that we have session token we generate opentok token
           	$token = $openTokAPI->generateToken($sessionId, [
                'exerciseireTime' => time()+30,
                'data'       => "OpenTok API for trinity."
           	]);

           	$request['session'] = $sessionId;
           	$request['token'] = $token;

    		$item = VideoCallSession::store($request);
    	DB::commit();
        $doctor = $request->user();
        $user = $item->receivable;
        $message = $doctor->fullname.' is calling you.';
        $push = new PushService('Calling...', $message);
        $push->pushToOne($user);

        return response()->json([
            'session' => $item->session,
            'token' => $item->token,
        ]);
    }

    /**
     * Get specific video session
     * 
     * @param  Array $request
     * @return  object $item
     */
    
    public function receive(Request $request)
    {
    	$user = $request->user();

        $item = $user->videoCallSessionReceivable()->find($request->id);

        return response()->json([
            'session' => $item->session,
            'token' => $item->token,
            'consultation' => $item->consultation,
            'doctor' => $item->consultation->doctor,
            'patient' => $item->consultation->user            
        ]);
    }
}
