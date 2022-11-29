<?php

namespace App\Traits;

trait Curl
{
    /**
     * Function used for get requests
     * 
     * @param string|nullable $url
     * @param array $header
     * @return json $reeponse
     */
    public function getRequest($url, $header = [])
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        
        if(count($header)){
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        $curl_response = curl_exec($curl);

        curl_close($curl);

        return $curl_response;
    }

    /**
     * Function used for post requests
     * 
     * @param string $url
     * @param array $header
     * @return json $reeponse
     */
    public function postRequest($url, $header, $payload)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($payload));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        
        $curl_response = curl_exec($curl);

        curl_close($curl);

        return $curl_response;
    }
}