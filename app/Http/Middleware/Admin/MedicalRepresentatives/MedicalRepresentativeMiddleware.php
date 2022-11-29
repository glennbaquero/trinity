<?php

namespace App\Http\Middleware\Admin\MedicalRepresentatives;

use App\Extendables\BaseMiddleware as Middleware;

class MedicalRepresentativeMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.medreps.crud'];
    }
}
