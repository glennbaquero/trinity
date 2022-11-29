<?php

namespace App\Http\Middleware\Admin\ActivityLogs;

use App\Extendables\BaseMiddleware as Middleware;

class ActivityLogMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.activity-logs.crud'];
    }
}
