<?php

namespace App\Http\Controllers\API\Care\Invoices;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Ecommerce\CreditInvoicePaynamicsProcessor;

use App\Models\CreditInvoices\CreditInvoice;

use DB;
use Storage;

class CreditInvoiceController extends Controller
{

    /**
	* Checkout credit invoice
	*
	* @param Illuminate\Http\Request $request
	* @return Illuminate\Http\Response
    */
   	public function checkout(Request $request)
   	{
   		$default_address = $request->user()->addresses()->where('default', true)->first() ?? $request->user()->addresses()->first();

   		DB::beginTransaction();
   			$invoice = CreditInvoice::store($request);
   		DB::commit();

			$form = $this->generatePaynamicsForm($invoice, $default_address);
   		
   		return response()->json([
   			'form' => $form,
   		]);
   	}

   	/**
	* Generate Paynamics form
	*
	* @param object $invoice
	* @return string $form
	*/
	public function generatePaynamicsForm($invoice, $default_address)
	{
		$paynamics = new CreditInvoicePaynamicsProcessor();
		$signature = $paynamics->createXML($invoice, $default_address);

        /** Create form */
      $form = '<html><head><style>.lds-dual-ring {display: inline-block;width: 64px;height: 64px;} .lds-dual-ring:after {content: " ";display: block;width:46px;height: 46px;margin: 1px;border-radius: 50%;border: 5px solid red;border-color: red transparent red transparent;animation: lds-dual-ring 1.2s linear infinite;} @keyframes lds-dual-ring { 0% { transform: rotate(0deg); } 100% {transform: rotate(360deg);}} .centered { position: fixed;top: 50%;left: 50%;margin-top: -50px;margin-left: -20px; }</style></head><body onload="submitForm()"><div class="centered"><div class="lds-dual-ring"></div></div><form id="paynamicsForm" name="form1" method="post" action="'. config('ecommerce.paynamics.gateway') .'">'.
          '<input type="hidden" name="paymentrequest" id="paymentrequest" value="'. $signature  .'" style="width:800px; padding: 10px;">'.
          '</form><script type="text/javascript">function submitForm() { document.getElementById("paynamicsForm").submit(); }</script></body></html>';

        return $form;
	}

  /**
  * Return to homepage after transaction
  *
  * @param Illuminate\Http\Request $request
  * @return Illuminate\Http\Response
    */
  public function return(Request $request)
  {
    DB::beginTransaction();
      
      $status = $request->user()->credit_invoices()->latest()->first()->status;
    
    DB::commit();

    return response()->json(compact('status'));
  }
}
