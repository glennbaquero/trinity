<?php

namespace App\Http\Controllers\API\Doctor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Faqs\Faq;

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
        
        $faq =  $faq->where('app', 1)->orderby('id', 'desc')->with('category');      
        return response()->json([
            'faqs' => $faq->get()
        ]);
    }

}