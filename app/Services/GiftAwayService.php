<?php

namespace App\Services;

use App\Traits\Curl;

class GiftAwayService
{
    use Curl;

    protected $url;
    protected $key;
    protected $private;

    /**
     * Create new instance
     * 
     * @return void
     */
    public function __construct()
    {
        $this->api = config('giftaway.api');   
        $this->key = config('giftaway.key');   
        $this->private = config('giftaway.private');   
    }

    /**
     * Returns a complete list of available merchants (eGift brands)
     */
    public function fetchMerchants($categoryid)
    {
        $service_api = $this->api . '/merchant/list';
        $header = [];
        $payload = ['apikey' => $this->key];
        
        if($categoryid) {
            $payload['categoryid'] = $categoryid;  
        }

        $response = $this->postRequest($service_api, $header, $payload);
        
        return json_decode($response);
    }

    /**
     * Returns the complete details of the merchant
     * 
     * @param $merchant_id
     */
    public function merchantDetails($merchant_id)
    {
        $service_api = $this->api . '/merchant/info';
        $header = [];
        $payload = ['apikey' => $this->key, 'merchantid' => $merchant_id];

        $response = $this->postRequest($service_api, $header, $payload);

        return json_decode($response);
    }

    /**
     * Return the complete list of merchant categories
     */
    public function merchantCategories()
    {
        $service_api = $this->api . '/category/list';
        $header = [];
        $payload = ['apikey' => $this->key];

        $response = $this->postRequest($service_api, $header, $payload);

        return json_decode($response);
    }

    /**
     * Place an order by denomination
     * 
     * @param object $user
     * @param array $items
     * @param string $denomination_id
     * @param string $denomination_value
     */
    public function byDenomination($user, $denomination_id, $denomination_value, $quantity)
    {
        $service_api = $this->api . '/order/process';
        $header = [];
        $items = [[$denomination_id, $quantity]];
        $total_value = $denomination_value * $quantity;
        $reference = $this->generateReference();

        $payload = [
            'apikey' => $this->key,
            'items' => json_encode($items),
            'referenceno' => $reference,
            'securehash' => $this->generateHash($reference, $total_value),
            'sendername' => $user->renderFullName(),
            'sendermobile' => $user->mobile_number,
            'senderemail' => $user->email,
            'recipientname' => $user->renderFullName(),
            'recipientmobile' => $user->mobile_number,
            'recipientemail' => $user->email,
        ];

        $response = $this->postRequest($service_api, $header, $payload);

        return json_decode($response);
    }

    /**
     * Generate unique reference for the transaction
     * 
     * @return string $reference
     */
    public function generateReference()
    {
        return strtoupper('trinity-' . bin2hex(random_bytes(3)));
    }

    /**
     * Generate secure hash SHA-1
     * 
     * @param string $reference
     * @param int $value
     * @return string $hash
     */
    public function generateHash($reference, $value)
    {
        $data = "{$this->key}:{$reference}:{$value}:{$this->private}";

        return sha1($data);
    }
}