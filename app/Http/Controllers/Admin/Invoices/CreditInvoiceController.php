<?php

namespace App\Http\Controllers\Admin\Invoices;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Ecommerce\CreditInvoicePaynamicsProcessor;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

use App\Models\Users\User;
use App\Models\CreditInvoices\CreditInvoice;

use Carbon\Carbon;

use App\Services\PushService;

class CreditInvoiceController extends Controller
{
    /**
     * Processing paynamics
     * 
     * @param  Requests $request 
     */
    public function processPaynamics(Request $request)
    {
        $processor = new CreditInvoicePaynamicsProcessor();

        /** Process Paynamics */
        return $processor->process($request);
    }

    /**
     * Paynamics success return
     * 
     */
    public function paynamicsReturn(Request $request)
    {
        $processor = new CreditInvoicePaynamicsProcessor();
        $route = $processor->processReturnResponse($request);

        return response()->json([

        ]);
    }

    /**
     * Paynamics cancel
     * 
     */
    public function paynamicsCancel()
    {
        return response()->json([

        ]);
    }
}
