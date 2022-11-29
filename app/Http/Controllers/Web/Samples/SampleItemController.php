<?php

namespace App\Http\Controllers\Web\Samples;

use App\Http\Controllers\Samples\SampleItemController as Controller;

class SampleItemController extends Controller
{
    protected $indexView = 'web.samples.index';
    protected $createView = 'web.samples.create';
    protected $showView = 'web.samples.show';
    protected $guard = 'web';
}
