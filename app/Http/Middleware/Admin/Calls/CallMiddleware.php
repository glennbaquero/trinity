<?php

namespace App\Http\Middleware\Admin\Calls;

use App\Extendables\BaseMiddleware as Middleware;

class CallMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.calls.crud'];
    }
}
