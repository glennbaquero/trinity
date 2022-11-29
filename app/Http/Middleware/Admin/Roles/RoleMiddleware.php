<?php

namespace App\Http\Middleware\Admin\Roles;

use App\Extendables\BaseMiddleware as Middleware;

class RoleMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.roles.crud'];
    }
}
