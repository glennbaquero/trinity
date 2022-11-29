<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountFetchController extends Controller
{
    public function fetchNotificationCount(Request $request) {
    	$user = $request->user();

    	$count = $user->unreadNotifications()->count();

    	return response()->json([
    		'count' => $count,
    	]);
    }
}
