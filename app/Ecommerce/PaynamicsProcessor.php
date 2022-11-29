<?php

namespace App\Ecommerce;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Models\Users\Admin;
use App\Models\Invoices\Invoice;

use App\Notifications\Care\CheckoutNotification;

use Auth;

class PaynamicsProcessor 
{

	protected $merchantKey;
	protected $merchantID;
	protected $invoice;

	public function __construct()
	{	
		$this->merchantID = config('ecommerce.paynamics.merchantid');
		$this->merchantKey = config('ecommerce.paynamics.merchantkey');

	}


	/**
	 * Create xml
	 * 
	 * @param  $invoice
	 * 
	 */
	public function createXML($invoice)
	{

		Log::info('Creating XML...');

		$this->invoice = $invoice;

		$_mid = $this->merchantID;
		$_requestid = $this->invoice->code;

		$_fname = $this->invoice->user->first_name;
		$_mname = '';
		$_lname = $this->invoice->user->last_name;
		$_addr1 = $this->invoice->renderFullAddress();
		$_addr2 = $this->invoice->renderFullAddress();
		$_city = $this->invoice->shipping_city;
		$_state = $this->invoice->shipping_city;
		$_country = 'Philippines';
		$_zip = $this->invoice->shipping_zip;
		$_email = $this->invoice->user->email;
		$_phone = $this->invoice->user->mobile_number;
		$_mobile = $this->invoice->user->mobile_number;
		$_ipaddress = config('ecommerce.paynamics.ipaddress');
		$_noturl = route('admin.checkout.process_paynamics'); // url where response is posted
		$_resurl = route('admin.checkout.paynamics_return'); //url of merchant landing page
		$_cancelurl = route('admin.checkout.paynamics-cancel');		
		$_clientip = $_SERVER['REMOTE_ADDR'];
		$_sec3d = "try3d";
		$_amount = number_format($this->invoice->grand_total, 2, '.', ''); // kindly set this to the total amount of the transaction. Set the amount to 2 decimal point before generating signature.
		$_currency = "PHP"; //PHP or USD

		$forSign = $_mid . $_requestid . $_ipaddress . $_noturl . $_resurl . $_fname . $_lname . $_addr1 . $_city . $_state . $_country . $_zip . $_email . $_phone . $_clientip . $_amount . $_currency . $_sec3d;


		$cert =  $this->merchantKey; //<-- your merchant key
        $_sign = hash("sha512", $forSign . $cert);		

        Log::info('Paynamics Signature: ' . $_sign);


		$strxml = "";
		$strxml = $strxml . "<?xml version=\"1.0\" encoding=\"utf-8\" ?>";
		$strxml = $strxml . "<Request>";
		$strxml = $strxml . "<orders>";
		$strxml = $strxml . "<items>";
		
		foreach ($invoice->invoiceItems as $item) {
			$strxml = $strxml . "<Items>";
				$strxml = $strxml . "<itemname>". $item->product->name ."</itemname><quantity>". 1 ."</quantity><amount>" . number_format($item->total_price, 2, '.', '') . "</amount>";		
			$strxml = $strxml . "</Items>";

			Log::info('Product Name : ' . $item->product->name);
			Log::info('Total Price : ' . number_format($item->total_price, 2, '.', ''));

		}	

		$strxml = $strxml . "<Items>";
			$strxml = $strxml . "<itemname> Shipping Fee </itemname><quantity>". 1 ."</quantity><amount>" . number_format($this->invoice->shipping_fee, 2, '.', '') . "</amount>";		
		$strxml = $strxml . "</Items>";

		Log::info('Shipping Fee: ' . $this->invoice->shipping_fee);

		$strxml = $strxml . "</items>";
		$strxml = $strxml . "</orders>";
		$strxml = $strxml . "<mid>" . $_mid . "</mid>";
		$strxml = $strxml . "<request_id>" . $_requestid . "</request_id>";
		$strxml = $strxml . "<ip_address>" . $_ipaddress . "</ip_address>";
		$strxml = $strxml . "<notification_url>" . $_noturl . "</notification_url>";
		$strxml = $strxml . "<response_url>" . $_resurl . "</response_url>";
		$strxml = $strxml . "<cancel_url>" . $_cancelurl . "</cancel_url>";
		$strxml = $strxml . "<mtac_url></mtac_url>"; // pls set this to the url where your terms and conditions are hosted
		$strxml = $strxml . "<descriptor_note>Trinity Philippines</descriptor_note>"; // pls set this to the descriptor of the merchant ""
		$strxml = $strxml . "<fname>" . $_fname . "</fname>";
		$strxml = $strxml . "<lname>" . $_lname . "</lname>";
		$strxml = $strxml . "<address1>" . $_addr1 . "</address1>";
		$strxml = $strxml . "<city>" . $_city . "</city>";
		$strxml = $strxml . "<state>" . $_city . "</state>";
		$strxml = $strxml . "<country>" . $_country . "</country>";
		$strxml = $strxml . "<zip>" . $_zip . "</zip>";
		$strxml = $strxml . "<secure3d>" . $_sec3d . "</secure3d>";
		$strxml = $strxml . "<trxtype>sale</trxtype>";
		$strxml = $strxml . "<email>" . $_email . "</email>";
		$strxml = $strxml . "<phone>" . $_phone . "</phone>";
		$strxml = $strxml . "<mobile>" . $_mobile . "</mobile>";
		$strxml = $strxml . "<client_ip>" . $_clientip . "</client_ip>";
		$strxml = $strxml . "<amount>" . $_amount . "</amount>";
		$strxml = $strxml . "<currency>" . $_currency . "</currency>";
		$strxml = $strxml . "<mlogo_url>https://app.trinityhealth.com.ph/images/logo_png.png</mlogo_url>";// pls set this to the url where your logo is hosted
		$strxml = $strxml . "<pmethod></pmethod>";
		$strxml = $strxml . "<signature>" . $_sign . "</signature>";
		$strxml = $strxml . "</Request>";

        Log::info('Encoding xml to base64');

        $b64string =  base64_encode($strxml);

        Log::info('XML encoded to base64: ' .  $b64string);

        return $b64string;

	}


	/**
	 * Process paynamics
	 * 
	 */
	public function process($request)
	{
        Log::info('Proccessing transaction...');


        $body = $request->paymentresponse;
        Log::info('PAYMENT RESPONSE: ' . $body);        

        $base64 = str_replace(" ", "+", $body);
        Log::info('Base64: ' . $base64);

        $body = $base64 . '+';
        
        Log::info('Modified Body: ' . $body);
        
        $body = base64_decode($body); // this will be the actual xml

        try {
            $data = new \SimpleXMLElement($body);
        } catch (\Exception $e) {
            $body = base64_decode($base64);
            $data = new \SimpleXMLElement($body);
        }

        Log::info('RECEIVED DATA: ' . $body);
        Log::info('RECEIVED CODE: ' . $data->responseStatus->response_code);        

        $reference_code = $data->application->request_id;

        /** Find invoice */
        $query = [
            'payment_status' => Invoice::UNPAID,
            'code' => $reference_code,
        ];

        $this->invoice = Invoice::where($query)->first();
        $tempInvoice = $this->invoice; 

        if($this->invoice) {
        	if($data->responseStatus->response_code == 'GR001' || $data->responseStatus->response_code == 'GR002') {
	            Log::info('GR001 or GR002');

                $forSign = $data->application->merchantid . $data->application->request_id . $data->application->response_id . $data->responseStatus->response_code . $data->responseStatus->response_message . $data->responseStatus->response_advise . $data->application->timestamp . $data->application->rebill_id;
                $cert = $this->merchantKey; //<-- your merchant key
                $_sign = hash("sha512", $forSign . $cert);

                Log::info('signedXMLResponse: ' . $data->application->signature);
                Log::info('Signature: ' . $_sign);

                /** Begin transaction */
                \DB::beginTransaction();
					
					/** Update payment status to PAID */
                	$this->invoice->payment_status = Invoice::PAID;
                	$this->invoice->response_code = $data->responseStatus->response_code;

                	// updated as of nov 20 by glenn
                	foreach ($this->invoice->invoiceItems as $invoice_item) {
            			$this->invoice->user->points()->create(['points' => $invoice_item->product->client_points * $invoice_item->quantity]);
                		
            			/** Check if product is not free */
                		if($invoice_item->product->is_free_product !== 1) {

                			$doctor = $invoice_item->doctor ? $invoice_item->doctor : null; 
							
							/** Check if there was a linked doctor to a specific item */
		                	if($adoctor) {
	                			$doctor->points()->create(['points' => $invoice_item->product->doctor_points  * $invoice_item->quantity]);

	                			/** Check if user is a first time buyer of doctor */
			                    if($user->firstTimeBuyer($doctor)) {
			                        $user->purchased()->attach($doctor->id);                                                
			                        $secretaries = $doctor->secretaries;
			                        foreach ($secretaries as $key => $secretary) {
			                            if($this->invoice->user_id != $secretary->id) {
			                                $secretary->points()->create(['points' => $invoice_item->product->secretary_points * $invoice_item->quantity]);
			                            }
			                        }         
			                    }	                			

		                	}
                		}
                		
                	}
                	
                	$this->invoice->save();

	                if($tempInvoice->user->firstTimeBuyer()) {
	                    $user->successfulReferral($tempInvoice->id);
	                }                	

				/** End transaction */
				\DB::commit();

				/**
				 * @todo
				 *
				 * Create a notification for success payment
				 */
				$tempInvoice->user->notify(new CheckoutNotification($tempInvoice));

                Log::info('Transaction done..');		

        	} else if($data->responseStatus->response_code == 'GR033') {


				/** Update payment status to PROCESSING */	
            	$this->invoice->response_code = $data->responseStatus->response_code;            	
            	$this->invoice->save();

            	/** Pending payment */
                Log::info('Transaction Pending...');            	

        	} else if($data->responseStatus->response_code == 'GR053') {

				/** Update payment status to TRANSACTION_CANCELLED */
            	$this->invoice->response_code = $data->responseStatus->response_code;            	
            	$this->invoice->save();

                Log::info('Transaction Cancelled...');
        	
        	} else {

				/** Update payment status to TRANSACTION_CANCELLED */
            	$this->invoice->response_code = $data->responseStatus->response_code;            		
				$this->invoice->save();

                Log::info('Transaction Failed.');
                Log::info('Response Code: ' . $data->responseStatus->response_code);
                Log::info('Message: ' . $data->responseStatus->response_message);
                Log::info('Advise: ' . $data->responseStatus->response_advise);

        	}
        }

	}

	/**
	 * Processing paynamics return response
	 * 
	 * @param  $request
	 */
    public function processReturnResponse($request)
    {
        $reference_code = base64_decode($request->requestid);
        Log::info('Reference Code: ' . $reference_code);
        
        $this->invoice = Invoice::where('code', $reference_code)->first();
        Log::info('Invoice: ' . $this->invoice);
        Log::info('Response Code: '. $this->invoice->response_code);

        if ($this->invoice->response_code == 'GR001' || $this->invoice->response_code == 'GR002')
        {
			/**
			 * return for success payment
			 * 
			 */
        }
        // check if pending payment
        else if ($this->invoice->response_code == 'GR033')
        {
			/**
			 * response for pending payment
			 * 
			 */
        }
        // check if payment was cancelled
        else if ($this->invoice->response_code == 'GR053')
        {
			/**
			 * response for cancelled payment
			 * 
			 */
        }
        //check if failed payment
        else
        {
			/**
			 * response for failed payment
			 * 
			 */
        }
    }	

}