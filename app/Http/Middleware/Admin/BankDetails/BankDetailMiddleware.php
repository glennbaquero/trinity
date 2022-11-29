<?php

namespace App\Http\Middleware\Admin\BankDetails;

use App\Extendables\BaseMiddleware as Middleware;

class BankDetailMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.bank-details.crud'];
    }
}
