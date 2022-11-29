<?php

namespace App\Helpers;

use App\Helpers\RouteChecker;

class GlobalChecker
{
    public $route;

    public function __construct($user = null)
    {
        /* Create the version checker */
        $this->route = new RouteChecker();
    }    
}