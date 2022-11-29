<?php

namespace App\Http\Middleware\Admin\Invoices;

use App\Extendables\BaseMiddleware as Middleware;

class InvoiceMiddleware extends Middleware
{
    public function __construct() {
        $this->permissions = ['admin.invoices.crud'];
    }
}
