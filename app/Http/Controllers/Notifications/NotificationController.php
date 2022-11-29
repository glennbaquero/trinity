<?php

namespace App\Http\Controllers\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Notifications\Notification;

class NotificationController extends Controller
{
    protected $indexView;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->indexView);
    }

    public function readAll(Request $request)
    {
        $user = $request->user()->readAllNotifications();

        return response()->json([
            'message' => 'All notifications has been marked as read.',
        ]);
    }

    public function read(Request $request, $id)
    {
    	$user = $request->user()->readNotification($id);

    	return response()->json([
    		'message' => 'Notification has been marked as read.',
    	]);
    }

    public function unread(Request $request, $id)
    {
    	$user = $request->user()->unreadNotification($id);

    	return response()->json([
    		'message' => 'Notification has been marked as unread.',
    	]);
    }
}
