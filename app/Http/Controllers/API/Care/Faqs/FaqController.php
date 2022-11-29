<?php

namespace App\Http\Controllers\API\Care\Faqs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Faqs\Faq;
use App\Models\Faqs\FaqCategory;

use DB;
use Carbon\Carbon;

class FaqController extends Controller
{

     /**
     * Fetch all Faqs
     * 
     * @return Illuminate\Http\Response
     */
    public function index(Faq $faq)
    {
        $faq =  $faq->where('app', 0)->orderby('id', 'asc')->with('category');    

        return response()->json([
            'faqs' => $faq->get()
        ]);
    }

}