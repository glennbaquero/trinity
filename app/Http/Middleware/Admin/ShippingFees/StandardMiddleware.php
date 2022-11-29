<?php

namespace App\Http\Middleware\Admin\ShippingFees;

use App\Extendables\BaseMiddleware as Middleware;

class StandardMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.shipping-standards.crud'];
    }
}
