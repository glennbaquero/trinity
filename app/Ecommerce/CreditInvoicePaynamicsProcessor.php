<?php

namespace App\Ecommerce;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use App\Models\Users\Admin;

use App\Models\CreditInvoices\CreditInvoice;

use Auth;

class CreditInvoicePaynamicsProcessor 
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
	public function createXML($invoice, $address)
	{

		Log::info('Creating XML...');

		$this->invoice = $invoice;

		$_mid = $this->merchantID;
		$_requestid = $this->invoice->reference_code;

		$_fname = $this->invoice->user->first_name;
		$_mname = '';
		$_lname = $this->invoice->user->last_name;
		$_addr1 = $address ? $address->renderFullAddress() : 'N/A';
		$_addr2 = $address ? $address->renderFullAddress() : 'N/A';
		$_city = $address ? $address->city->name : 'N/A';
		$_state = $address ? $address->city->name : 'N/A' ;
		$_country = 'Philippines';
		$_zip = $address ? $address->city->zip : 'N/A';
		$_email = $this->invoice->user->email;
		$_phone = $this->invoice->user->mobile_number;
		$_mobile = $this->invoice->user->mobile_number;
		$_ipaddress = config('ecommerce.paynamics.ipaddress');
		$_noturl = route('admin.credit-package.process_paynamics'); // url where response is posted
		$_resurl = route('admin.credit-package.paynamics_return'); //url of merchant landing page
		$_cancelurl = route('admin.credit-package.paynamics-cancel');		
		$_clientip = $_SERVER['REMOTE_ADDR'];
		$_sec3d = "try3d";
		$_amount = number_format($this->invoice->total, 2, '.', ''); // kindly set this to the total amount of the transaction. Set the amount to 2 decimal point before generating signature.
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
		
		$strxml = $strxml . "<Items>";
			$strxml = $strxml . "<itemname>". $this->invoice->package->name ."</itemname><quantity>". 1 ."</quantity><amount>" . number_format($this->invoice->total, 2, '.', '') . "</amount>";		
		$strxml = $strxml . "</Items>";

		Log::info('Credit Package Name : ' . $this->invoice->package->name);
		Log::info('Credit Package Price : ' . number_format($this->invoice->total, 2, '.', ''));

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
            'status' => CreditInvoice::PENDING,
            'reference_code' => $reference_code,
        ];

        $this->invoice = CreditInvoice::where($query)->first();


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
                	$this->invoice->status = CreditInvoice::PAID;
                	$this->invoice->response = $data->responseStatus->response_code;
                	$this->invoice->save();

                	$this->invoice->user->processCredit($this->invoice->package->credits);

				/** End transaction */
				\DB::commit();

				/**
				 * @todo
				 *
				 * Create a notification for success payment
				 */
                
                Log::info('Transaction done..');		

        	} else if($data->responseStatus->response_code == 'GR033') {


				/** Update payment status to PROCESSING */	
            	$this->invoice->response = $data->responseStatus->response_code;            	
            	$this->invoice->save();

            	/** Pending payment */
                Log::info('Transaction Pending...');            	

        	} else if($data->responseStatus->response_code == 'GR053') {

				/** Update payment status to TRANSACTION_CANCELLED */
            	$this->invoice->response = $data->responseStatus->response_code;            	
            	$this->invoice->save();

                Log::info('Transaction Cancelled...');
        	
        	} else {

				/** Update payment status to TRANSACTION_CANCELLED */
            	$this->invoice->response = $data->responseStatus->response_code;            		
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
        
        $this->invoice = CreditInvoice::where('reference_code', $reference_code)->first();
        Log::info('Invoice: ' . $this->invoice);
        Log::info('Response Code: '. $this->invoice->response);

        if ($this->invoice->response == 'GR001' || $this->invoice->response == 'GR002')
        {
			/**
			 * return for success payment
			 * 
			 */
        }
        // check if pending payment
        else if ($this->invoice->response == 'GR033')
        {
			/**
			 * response for pending payment
			 * 
			 */
        }
        // check if payment was cancelled
        else if ($this->invoice->response == 'GR053')
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