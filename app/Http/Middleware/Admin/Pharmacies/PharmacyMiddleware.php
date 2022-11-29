<?php

namespace App\Http\Middleware\Admin\Pharmacies;

use App\Extendables\BaseMiddleware as Middleware;

class PharmacyMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.pharmacies.crud'];
    }
}
