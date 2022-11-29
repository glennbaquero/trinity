<?php

namespace App\Http\Controllers\Admin\Samples;

use App\Http\Controllers\Samples\SampleItemController as Controller;

class SampleItemController extends Controller
{
    protected $indexView = 'admin.samples.index';
    protected $createView = 'admin.samples.create';
    protected $showView = 'admin.samples.show';

    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Samples\SampleItemMiddleware', 
            ['only' => ['index', 'create', 'store', 'show', 'update', 'archive', 'restore']]
        );
    }
}
