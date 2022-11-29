<?php

namespace App\Http\Controllers\Admin\ActivityLogs;

use App\Http\Controllers\ActivityLogs\ActivityLogController as Controller;

class ActivityLogController extends Controller
{
    protected $indexView = 'admin.activity-logs.index';

	public function __construct()
	{
		$this->middleware('App\Http\Middleware\Admin\ActivityLogs\ActivityLogMiddleware', [
			['only' => ['index']]
		]);
	}
}
