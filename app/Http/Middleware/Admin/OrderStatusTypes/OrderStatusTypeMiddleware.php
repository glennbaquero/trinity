<?php

namespace App\Http\Middleware\Admin\OrderStatusTypes;

use App\Extendables\BaseMiddleware as Middleware;

class OrderStatusTypeMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.status-types.crud'];
    }
}
