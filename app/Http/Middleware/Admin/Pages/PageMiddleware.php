<?php

namespace App\Http\Middleware\Admin\Pages;

use App\Extendables\BaseMiddleware as Middleware;

class PageMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.pages.crud'];
    }
}
