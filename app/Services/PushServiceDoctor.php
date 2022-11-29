<?php

namespace App\Services;

use App\Models\Notifications\DeviceToken;
use GuzzleHttp\Client;

class PushServiceDoctor
{

    protected $title;
    protected $message;
    protected $image;

    public function __construct($title, $message, $image = null) {
        $this->title = $title;
        $this->message = $message;
        $this->image = $image;
    }

    public function pushToAll()
    {
        $this->push(DeviceToken::all());
    }

    public function pushToMany($users)
    {
        $device_tokens = [];

        foreach ($users as $user) {
            foreach ($user->deviceTokens as $device_token) {
                array_push($device_tokens, $device_token);
            }
        }

        $this->push($device_tokens);

    }

    public function pushToOne($user)
    {
        $device_tokens = [];

        foreach ($user->deviceTokens as $device_token) {
            array_push($device_tokens, $device_token);
        }

        $this->push($device_tokens);

    }

    protected function push($device_tokens) {

        $ios = [];
        $android = [];

        foreach($device_tokens as $device_token) {
            switch($device_token->platform) {
                case 'iOS':
                    array_push($ios, $device_token->token);
                    break;

                case 'Android':
                    array_push($android, $device_token->token);
                    break;
            }
        }

        // short-circuit if no tokens found
        if(!$ios && !$android) {
            return false;
        }


        $this->deliverPayload([
            'ios' => $ios,
            'android' => $android,
            'title' => $this->title,
            'message' => strip_tags($this->message),
            'sound' => $this->image
        ]);
    }

    public function deliverPayload($payload)
    {
        $client = new Client();
        $client->post(config('push.url_doc'), [
            'form_params' => $payload
        ]);
    }

}