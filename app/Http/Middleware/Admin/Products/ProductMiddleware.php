<?php

namespace App\Http\Middleware\Admin\Products;

use App\Extendables\BaseMiddleware as Middleware;

class ProductMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.products.crud'];
    }
}
