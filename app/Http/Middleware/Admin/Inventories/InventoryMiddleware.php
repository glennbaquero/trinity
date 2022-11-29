<?php

namespace App\Http\Middleware\Admin\Inventories;

use App\Extendables\BaseMiddleware as Middleware;

class InventoryMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.inventories.crud'];
    }
}
