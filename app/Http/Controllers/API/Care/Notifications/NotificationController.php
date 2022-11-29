<?php

namespace App\Http\Controllers\API\Care\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use App\Models\Notifications\Notification;

use DB;

class NotificationController extends Controller
{

    /**
     * Fetch paginated notifications
     * 
     * @return 
     */
    public function notifications(Request $request)
    {   
        
        $user = request()->user();
        $page = $request->page;
        return response()->json([
            'items' => $user->notifications()->paginate(10),
        ]);
    }

    /**
     * Read Notificationn
     * 
     * @return 
     */
    public function readNotification(Request $request)
    {
        DB::beginTransaction();
            Notification::find($request->id)->update(['read_at' => Carbon::now()]);
        DB::commit();

        return response()->json([
            'message' => 'success',
        ]);
    }
}
