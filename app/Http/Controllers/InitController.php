<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class InitController extends Controller
{
	public function LandingPage()
	{
		 return redirect()->route('admin.login');
	}
}