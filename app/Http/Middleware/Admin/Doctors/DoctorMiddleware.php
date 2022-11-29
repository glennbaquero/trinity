<?php

namespace App\Http\Middleware\Admin\Doctors;

use App\Extendables\BaseMiddleware as Middleware;

class DoctorMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.doctors.crud'];
    }
}
