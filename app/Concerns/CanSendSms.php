<?php

namespace App\Concerns;

trait CanSendSms
{
    public function sendMessage($recipients, $message)
    {
        $apiKey = 'f04736cb-aa5c-4957-ac0b-4d0eeb743796';

        $apiSecret = 'W3Qz+RWRh0aCkXu7hN2P6onnP5BtkFfI';

        $accountApiCredentials = $apiKey.':'.$apiSecret;

        $base64Credentials = base64_encode($accountApiCredentials);

        $authHeader = 'Authorization: Basic '.$base64Credentials;

        $authEndpoint = 'https://rest.mymobileapi.com/Authentication';

        $authOptions = [
            'http' => [
                'header' => $authHeader,
                'method' => 'GET',
                'ignore_errors' => true,
            ],
        ];
        $authContext = stream_context_create($authOptions);

        $result = file_get_contents($authEndpoint, false, $authContext);

        $authResult = json_decode($result);

        $status_line = $http_response_header[0];
        preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
        $status = $match[1];

        if ($status === '200') {
            $authToken = $authResult->{'token'};
        } else {
            throw new \Exception('Something Went Wrong Sms Not Send');
        }

        $sendUrl = 'https://rest.mymobileapi.com/bulkmessages';

        $authHeader = 'Authorization: Bearer '.$authToken;

        $sendData = '{ "messages" : [ { "content" : "'.$message.'", "destination" : "{'.$recipients.'}" } ] }';

        $options = [
            'http' => [
                'header' => ['Content-Type: application/json', $authHeader],
                'method' => 'POST',
                'content' => $sendData,
                'ignore_errors' => true,
            ],
        ];
        $context = stream_context_create($options);

        $sendResult = file_get_contents($sendUrl, false, $context);

        $status_line = $http_response_header[0];
        preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
        $status = $match[1];

        if ($status === '200') {
            return true;
        } else {
            throw new \Exception('Something Went Wrong Sms Not Send');
        }
    }
}
