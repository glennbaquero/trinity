<?php

namespace App\Http\Controllers\Admin\Points;

use App\Http\Controllers\Controller;

class PointController extends Controller
{
    
    public function __construct() {
        $this->middleware('App\Http\Middleware\Admin\Points\PointMiddleware', 
            ['only' => ['index']]
        );
    }

	public function index()
	{
		return view('admin.points.index');
	}

}
