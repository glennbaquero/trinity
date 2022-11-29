<?php

namespace App\Http\Middleware\Admin\TargetCalls;

use App\Extendables\BaseMiddleware as Middleware;

class TargetCallMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.target-calls.crud'];
    }
}
