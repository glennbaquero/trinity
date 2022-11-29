<?php

namespace App\Http\Middleware\Admin\ShippingFees;

use App\Extendables\BaseMiddleware as Middleware;

class ExpressMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.shipping-expresses.crud'];
    }
}
