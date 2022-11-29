<?php

namespace App\Http\Controllers\Admin\Analytics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Users\Admin;
use App\Models\Samples\SampleItem;
use App\Models\Generals\ActivityLog;

class DashboardAnalyticsController extends Controller
{
    public function fetch() {
    	$admins = new Admin;
    	$admin_chart = $this->getAdminsAnalytics($admins);

    	return response()->json([
    		'admins' => $admins->count(),
            'admins_inactive' => $admins->onlyTrashed()->count(),
    		'admin_chart' => $admin_chart,
            'items' => SampleItem::count(),
    	]);
    }

    protected function getAdminsAnalytics($admins) {

    	if (!$admins->count()) {
    		return false;
    	}

    	return [
    		[
    			'label' => 'Active',
    			'data' => $admins->count(),
    			'backgroundColor' => '#28a745',
    		],
    		[
    			'label' => 'Inactive',
    			'data' => $admins->onlyTrashed()->count(),
    			'backgroundColor' => '#dc3545',
    		],
    	];
    }
}
