<?php

namespace App\Http\Middleware\Admin\Redeems;

use App\Extendables\BaseMiddleware as Middleware;

class RedeemMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.redeems.crud'];
    }
}
