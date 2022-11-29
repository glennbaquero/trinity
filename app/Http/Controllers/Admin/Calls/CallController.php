<?php

namespace App\Http\Controllers\Admin\Calls;

use App\Models\Calls\Call;

use Illuminate\Http\Request;

use App\Http\Requests\Admin\Calls\CallRequest;
use App\Http\Controllers\Controller;

use App\Notifications\Team\ApproveCallPlan;
use App\Notifications\Team\RejectCallPlan;
use App\Notifications\Team\DoctorCallPlanNotif;

use App\Models\Users\MedicalRepresentative;
use App\Models\Users\Admin;
use Notification;

class CallController extends Controller
{
    
    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Calls\CallMiddleware', 
            ['only' => ['index', 'create', 'update', 'archive', 'restore', 'reject', 'approve']]
        );
    }

    /**
    * Show calls table
    *
    * @return Illuminate\Http\Response
    */
	public function index()
	{
        $statuses = [
            ['id' => 0, 'name' => 'Pending'],
            ['id' => 1, 'name' => 'Approved'],
            ['id' => 2, 'name' => 'Rejected']
        ];

        $medreps = MedicalRepresentative::get();

		return view('admin.calls.index', [
            'statuses' => json_encode($statuses),
            'medreps' => $medreps
        ]);
	}


    /**
    * Show create call form
    *
    * @return Illuminate\Http\Response
    */
	public function create()
	{
		return view('admin.calls.create');
	}


    /**
    * View a specific call
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
	public function show(int $id)
	{
		$call = Call::withTrashed()->findOrFail($id);

        return view('admin.calls.show', compact('call'));
	}


    /**
    * Store a call to DB
    *
    * @param App\Http\Requests\Admin\Calls\CallRequest $request
    * @return Illuminate\Http\Response
    */
	public function store(CallRequest $request)
	{
		\DB::beginTransaction();
		$item = Call::store($request);
        $item->update(['status' => 1]);
		\DB::commit();

		$message = "You have successfully created Call #{$item->id}!";
		$redirect = $item->renderShowUrl();

		return response()->json(compact('message', 'redirect'));
	}


    /**
    * Update a specific call
    *
    * @param App\Http\Requests\Admin\Calls\CallRequest $request
    * @param int $id
    * @return Illuminate\Http\Response
    */
	public function update(CallRequest $request, int $id)
    {
        $item = Call::withTrashed()->findOrFail($id);
        $message = "You have successfully updated Call #{$item->id}";

        $item = Call::store($request, $item);

        return response()->json(compact('message'));
    }


    /**
    * Archive a specific call
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function archive(int $id)
    {
        $item = Call::withTrashed()->findOrFail($id);
        $item->archive();

        return response()->json([
            'message' => "You have successfully archived Call #{$item->id}",
        ]);
    }


    /**
    * Restore a specific call
    *
    * @param int $id
    * @return Illuminate\Http\Response
    */
    public function restore(int $id)
    {
        $item = Call::withTrashed()->findOrFail($id);
        $item->unarchive();

        return response()->json([
            'message' => "You have successfully restored Call #{$item->id}",
        ]);
    }

    /**
    * Approve calls
    *
    * @param Illuminate\Http\Request $request
    * @return Illuminate\Http\Response
    */
    public function approve(Request $request)
    {
        $items = Call::withTrashed()->where('status', 0)->whereIn('id', $request->items);

        /** Start transaction */
        \DB::beginTransaction();
        
            $items->update(['status' => 1]);
        
        /** End transaction */
        \DB::commit();

        foreach ($items->get() as $item) {
            $this->sendNotification($item, true);
            $this->sendNotificationToDoctor($item);
        }

        $message = 'You have successfully approved all selected calls';

        if ($items->count() === 1) {
            $message = "You have successfully approved Call #{$items->first()->id}";
        }

        return response()->json([
            'message' => $message,
            'redirectUrl' => route('admin.calls.index')
        ]);
    }


    /**
    * Reject calls
    *
    * @param Illuminate\Http\Request $request
    * @return Illuminate\Http\Response
    */
    public function reject(Request $request)
    {
        $items = Call::withTrashed()->where('status', 0)->whereIn('id', $request->items);
        
        /** Start transaction */
        \DB::beginTransaction();        
        
            $items->update(['status' => 2]);

        /** End transaction */
        \DB::commit();            

        foreach ($items->get() as $item) {
            $this->sendNotification($item, false);
        }

        $message = 'You have successfully rejected all selected calls';

        if ($items->count() === 1) {
            $message = "You have successfully rejected Call #{$items->first()->id}";
        }

        return response()->json([
            'message' => $message,
            'redirectUrl' => route('admin.calls.index')
        ]);
    }

    /**
    * Reject all calls at once
    *
    * @param Illuminate\Http\Request $request
    * @return Illuminate\Http\Response
    */
    public function rejectAll(Request $request)
    {
        $items = Call::withTrashed()->where('status', 0)->whereIn('id', $request->items);

        \DB::beginTransaction();
        
            $items->update(['status' => 2]);
        
        \DB::commit();

        foreach ($items->get() as $item) {
            $this->sendNotification($item, false);
        }

        return response()->json([
            'message' => "You have successfully approved all selected calls",
        ]);
    }

    /**
     * Send notification for admins
     * 
     * @param  $item
     * @param  $call
     */
    public function sendNotification($item, $status)
    {
        $admins = Admin::get();
        foreach ($admins as $key => $admin) {
            if($admin->hasAnyPermission('admin.calls.crud')) {
                if($status) {
                    Notification::send($item->medicalRepresentative, new ApproveCallPlan($item->medicalRepresentative, $item));
                } else {
                    Notification::send($item->medicalRepresentative, new RejectCallPlan($item->medicalRepresentative, $item));
                }
            }
        }
    }

    public function sendNotificationToDoctor($item)
    {
        Notification::send($item->doctor, new DoctorCallPlanNotif($item->doctor, $item));
    }

}
