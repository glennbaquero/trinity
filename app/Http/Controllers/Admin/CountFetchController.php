<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Samples\SampleItem;

class CountFetchController extends Controller
{
    public function fetchNotificationCount(Request $request) {
    	$user = $request->user();

    	$count = $user->unreadNotifications()->count();

    	return response()->json([
    		'count' => $count,
    	]);
    }

    public function fetchSampleItemCount(Request $request) {
    	$count = SampleItem::where('status', SampleItem::STATUS_PENDING)->count();

    	return response()->json([
    		'count' => $count,
    	]);
    }
}
