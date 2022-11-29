<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Paynamics Credentials
    |--------------------------------------------------------------------------
    */
    'paynamics' => [
        'ipaddress' => env('PAYNAMICS_IP_ADDRESS', ''),     
        'merchantid' => env('PAYNAMICS_MERCHANT_ID', ''),
        'merchantkey' => env('PAYNAMICS_MERCHANT_KEY', ''),
        'gateway' => env('PAYNAMICS_GATEWAY_URL', '')
    ],

];
