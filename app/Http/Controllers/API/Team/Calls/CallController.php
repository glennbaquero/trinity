<?php

namespace App\Http\Controllers\API\Team\Calls;

use App\Models\Calls\Call;

use App\Http\Requests\API\Team\Calls\TeamCallStoreRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Helpers\FileHelpers;

use App\Notifications\Team\AddCallPlan;
use App\Models\Users\Admin;
use Notification;

use Storage;

class CallController extends Controller
{
    
    /**
    * Save a call to DB
    *
    * @param App\Http\Requests\API\Team\Calls\TeamCallStoreRequest $request
    * @return Illuminate\Http\Response
    */
	public function store(TeamCallStoreRequest $request)
	{
		\DB::beginTransaction();
			$call = $request->user()->calls()->create($request->all());
		\DB::commit();

        $this->sendNotification($request, $call);

		$calls = $this->refreshCallLists($request);

		return response()->json([
			'title' => 'Created call',
			'message' => 'Call successfully added',
			'calls' => $calls
		]);
	}


    /**
    * Update a specific call
    *
    * @param App\Http\Requests\API\Team\Calls\TeamCallStoreRequest $request
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function edit(TeamCallStoreRequest $request)
    {

        $vars = $request->only('agenda', 'notes');

        if($request->arrived_at != 'null') {
            $vars['arrived_at'] = $request->arrived_at;

            if($request->left_at != 'null') {
                $vars['left_at'] = $request->left_at;
            }
        }



        \DB::beginTransaction();
            $request->user()->calls()
                ->where('id', $request->id)
                ->update($vars);
            $this->uploadAttachments($request);
        \DB::commit();

        $call = $this->refreshCallLists($request);

        return response()->json([
            'title' => 'Updated call',
            'message' => 'Call successfully updated',
            'call' => $call,
        ]);
    }

	/**
    * Remove a specific call
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function remove(Request $request)
    {
    	$call = $request->user()->calls()->where('id', $request->id);
        \DB::beginTransaction();
        	$call->update(['reason' => $request->reason]);
            $call->delete();
        \DB::commit();

        $calls = $this->refreshCallLists($request);

        return response()->json([
			'title' => 'Deleted call',
			'message' => 'Call successfully deleted',
			'calls' => $calls,
		]);
	}



    public function uploadAttachments($request) {
        
        $attachments = $request['attachments'];

        if($attachments) {
            foreach($attachments as $file) {
                
                $fileDetails = explode(",", $file->getClientOriginalName());

                $file_path = FileHelpers::store($file, '/call-attachments');
                $request->user()->calls()->find($request->id)->callAttachments()->create([
                    'type' => $fileDetails[0],
                    'name' => $fileDetails[1],
                    'file_path' => $file_path,
                ]);
            }
        }

        if($request->signature) {
            $signature = $request->signature;

            $signature = str_replace('data:image/png;base64,', '', $signature);
            $signature = str_replace(' ', '+', $signature);
            $file_path = 'public/call-attachments/'. str_random(10).'.'.'png';
            \Storage::put($file_path, base64_decode($signature));

            $request->user()->calls()->find($request->id)->callAttachments()->create([
                'type' => 2,
                'name' => 'signature',
                'file_path' => $file_path,
            ]);
        } 


    }

    /**
     * Send notification for admins
     * 
     * @param  $request
     * @param  $call
     */
    public function sendNotification($request, $call)
    {
        $admins = Admin::get();
        foreach ($admins as $key => $admin) {
            if($admin->hasAnyPermission('admin.calls.crud')) {
                Notification::send($admin, new AddCallPlan($admin, $call));                
            }
        }
    }

    public function refreshCallLists($request)
    {
        $now = Carbon::now();
        $calls = $request->user()->calls()
                ->with('doctor', 'callAttachments')
                ->where(\DB::raw('year(scheduled_date)'), $now->format('Y'))
                ->where('status', 1)
                ->get();

        return $calls;
    }

}
