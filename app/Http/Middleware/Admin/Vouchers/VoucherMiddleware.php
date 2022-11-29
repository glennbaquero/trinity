<?php

namespace App\Http\Middleware\Admin\Vouchers;

use App\Extendables\BaseMiddleware as Middleware;

class VoucherMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.vouchers.crud'];
    }
}
