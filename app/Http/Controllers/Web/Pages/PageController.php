<?php

namespace App\Http\Controllers\Web\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pages\Page;
use App\Models\Pages\PageItem;

class PageController extends Controller
{
	public function showHome() {
	    \Auth::logout();
        return view('web.pages.home');
	}

	/**
	 * Show doctor password reset sucess page
	 * 
	 */
	public function doctor()
	{
		return view('web.pages.doctor.password-reset-success');
	}

	public function privacy() 
	{
		$content = PageItem::where('slug', 'app_store_privacy_and_policy')->first()->content;
		return view('web.pages.privacy-policy', [
			'content' => $content
		]);
	}
}
