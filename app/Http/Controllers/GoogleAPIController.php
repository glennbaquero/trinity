<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Http\Requests\GoogleFetchPositionRequest;

class GoogleAPIController extends Controller
{
    public function fetchAddressPosition(GoogleFetchPositionRequest $request) {
    	$string = str_replace (" ", "+", urlencode($request->input('address')));
    	$key = config('web.google.api-key');
        $url = "https://maps.googleapis.com/maps/api/geocode/json?key={$key}&address={$string}&components=country:PH";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);

        if ($response['status'] == 'OK') {
            $lat = $response['results'][0]['geometry']['location']['lat'];
            $lng = $response['results'][0]['geometry']['location']['lng'];
        } else {
        	throw ValidationException::withMessages([
        		'message' => isset($response['error_message']) ? $response['error_message'] : 'Something went wrong, please try again later.',
        	]);
        }

        return response()->json([
        	'latitude' => $lat,
        	'longitude' => $lng,
        	'message' => 'You have successfully fetch your address position',
        ]);
    }
}
