<?php

namespace App\Http\Middleware\Admin\Points;

use App\Extendables\BaseMiddleware as Middleware;

class PointMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.points.index'];
    }
}
