<?php

namespace App\Http\Controllers\API\Care\Invoices;

use App\Ecommerce\PaynamicsProcessor;

use App\Models\Carts\Cart;
use App\Models\Carts\CartItem;
use App\Models\Invoices\Invoice;
use App\Models\Invoices\InvoiceItem;
use App\Models\StatusTypes\StatusType;

use App\Http\Requests\API\Care\Invoices\UpdateBasicInfoRequest;
use App\Http\Controllers\API\Care\Invoices\InvoiceFetchController;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Notifications\Care\CheckoutNotification;

use App\Helpers\FileHelpers;
use DB;
use Storage;

class InvoiceController extends Controller
{

	public function get(Request $request)
	{
		$user = $request->user();

		if($request->status == 'cancelled') {
			$invoices = $user->invoices()->orderBy('created_at', 'desc')->get();

			$orders = collect($invoices)->map(function($invoice) {
				if($invoice->status->action_type == 3) {
					return $invoice->getUserInvoice();
				}
			})->filter();

		} else {

			$invoices = $request->user()->invoices()->orderBy('created_at', 'desc')->where('completed', $request->completed)->get();
			$orders = $invoices->map(function($invoice) {
				return $invoice->getUserInvoice();
			});

		}

		return response()->json(compact('orders'));
	}

	/**
	* Create invoice
	*
	* @param object $request
	* @param object $pendingCart
	*/
	public function createInvoice($request, $pendingCart)
	{
		$body = $request->except(['deposit_slip', 'shipping_method', 'voucher_type', 'voucher', 'document']);
		$body['shipping_method'] = 0;
		$body['status_id'] = StatusType::where('order', 0)->first()->id;
		$body['cart_id'] = $pendingCart->id;
		$body['invoice_number'] = Invoice::generateOrderNumber();
		$body['code'] = Invoice::generateRefCode();
		$body['shipping_fee'] = (int) $request->shipping_fee;
		$body['sub_total'] = (int) $request->sub_total;
		$body['grand_total'] = (int) $request->grand_total;
		$body['voucher'] = is_numeric($request->voucher) ? $request->voucher : null; 
		if($request->hasFile('document')) {
			$body['discount_card_path'] = $request->file('document')->store('documents', 'public');
		}

		DB::beginTransaction();

			$invoice = $request->user()->invoices()->create($body);
		
			if(is_numeric($request->voucher)) {
				$invoice->usedVoucher()->create([
					'voucher_id' => $request->voucher_type == 'global'? $request->voucher : null,
					'user_voucher_id' => $request->voucher_type == 'redeemable'? $request->voucher : null,
					'user_id' => request()->user()->id,
					'total_discount' => $invoice->total_discount
				]);
			}

		DB::commit();

		return $invoice;
	}

	/**
	* Create an invoice item for each cart yaz_itemorder(id, args)
	*
	* @param array $cartItems
	* @param object $invoice
    */
	public function createInvoiceItems($cartItems, $invoice)
	{
		DB::beginTransaction();
		foreach ($cartItems as $cartItem) {
			if(!$cartItem->product->deleted_at) {
				$invoiceItem = $invoice->invoiceItems()->create([
					'product_id' => $cartItem->product->id,
					'data' => json_encode($cartItem->product),
					'quantity' => $cartItem->quantity,
					'price' => $cartItem->product->price,
					'total_price' => $cartItem->quantity * $cartItem->product->price,

					/** Update from phase5 */
					'doctor_id' => $cartItem->doctor_id,
					'prescription_path' => $cartItem->prescription_path,
					'consultation_id' => $cartItem->consultation_id 
				]);
				$cartItem->product->inventory->deductStocksBy($invoiceItem->quantity);
			}
		}
		DB::commit();
	}
    
    /**
	* Checkout cart
	*
	* @param Illuminate\Http\Request $request
	* @return Illuminate\Http\Response
    */
	public function checkout(Request $request)
	{
		$form = null;
		$user = $request->user();

		$pendingCart = $user->carts->where('completed', 0)->first();

		DB::beginTransaction();

			$invoice = $this->createInvoice($request, $pendingCart);

			$this->createInvoiceItems($pendingCart->cartItems, $invoice);

		DB::commit();

		if ($invoice->payment_method == Invoice::PAYNAMICS) {
			$form = $this->generatePaynamicsForm($invoice);
		}

		if ($invoice->payment_method == Invoice::EWALLET) {
			$user->processCredit('-' . $invoice->grand_total);
			$invoice->update(['payment_status' => true]);

			$invoice->distributePoints();
			if($user->firstTimeBuyer()) {
				$user->successfulReferral($invoice->id);
            }
			
		}

		$pendingCart->completed = 1;
		$pendingCart->save();

		if($invoice->payment_method != Invoice::PAYNAMICS) {
			$request->user()->notify(new CheckoutNotification($invoice));
		}

		return response()->json([
			'form' => $form,
			'invoice' => $invoice->getUserInvoice()
		]);
	}

	public function update(UpdateBasicInfoRequest $request) {
		if ($request->new_address) {
			DB::beginTransaction();
	            $addresses = $request->user()->addresses();
	            $body = $request->except(['new_address', 'shipping_name', 'shipping_email', 'shipping_mobile']);

	            if ($request->default) {
	                $default = $addresses->where('default', true);
	                $default->update(['default' => false]);
	            }

	            $newAddress = $addresses->create($body);
	        DB::commit();

	        return response()->json([
	            'address' => $newAddress->getUserAddresses()
	        ]);
		}
    }


	/**
	* Generate Paynamics form
	*
	* @param object $invoice
	* @return string $form
	*/
	public function generatePaynamicsForm($invoice)
	{
		$paynamics = new PaynamicsProcessor();
		$signature = $paynamics->createXML($invoice);

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
			
			$status = $request->user()->invoices()->latest()->first()->payment_status;
		
		DB::commit();

		return response()->json(compact('status'));
	}

	/**
	* Upload deposit slip
	*
	* @param Illuminate\Http\Request $request
	* @return Illuminate\Http\Response
    */
	public function uploadDepositSlip(Request $request) 
	{
		$invoice = Invoice::find($request->id);
		DB::beginTransaction();
            $file_path = $request->file('deposit_slip_path')->store('deposit-slip', 'public');
			$invoice->update([ 'deposit_slip_path' => $file_path ]);
		DB::commit();

		return response()->json([
			'message' => 'uploaded',
			'response' => 200,
			'link' => $invoice->renderImagePath('deposit_slip_path')
		]);
	}

	/**
	* Fetch Profile : Orders
	*
	* @param Illuminate\Http\Request $request
	* @return Illuminate\Http\Response
    */
   	public function fetch(Request $request)
   	{
   		 $fetch_invoices = new InvoiceFetchController($request);
   		 $invoices = $fetch_invoices->fetch($request);

   		 return response()->json([
   		 	'invoices' => $invoices->original['items'],
   		 ]);

   	}

}
