<?php
namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class InitController extends Controller
{
	public function message()
	{
        return view('web.auth.verification.success');
	}
}
