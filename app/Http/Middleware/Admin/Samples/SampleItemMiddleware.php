<?php

namespace App\Http\Middleware\Admin\Samples;

use App\Extendables\BaseMiddleware as Middleware;

class SampleItemMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.sample-items.crud'];
    }
}
