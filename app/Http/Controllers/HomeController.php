<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request)
    {
        // $apiKey = 'f04736cb-aa5c-4957-ac0b-4d0eeb743796';
        // $apiSecret = 'W3Qz+RWRh0aCkXu7hN2P6onnP5BtkFfI';
        // $accountApiCredentials = $apiKey . ':' . $apiSecret;
        
        // $base64Credentials = base64_encode($accountApiCredentials);
    
        // $authHeader = 'Authorization: Basic ' . $base64Credentials;

        // $authEndpoint = 'https://rest.mymobileapi.com/Authentication';

        // $authOptions = array(
        //     'http' => array(
        //         'header'  => $authHeader,
        //         'method'  => 'GET',
        //         'ignore_errors' => true
        //     )
        // );
        // $authContext  = stream_context_create($authOptions);

        // $result = file_get_contents($authEndpoint, false, $authContext);

        // $authResult = json_decode($result);

        // $status_line = $http_response_header[0];
        // preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
        // $status = $match[1];

        // if ($status === '200') {
        //     $authToken = $authResult->{'token'};

        //     var_dump($authResult);
        // } else {
        //     var_dump($authResult);
        // }

        // $sendUrl = 'https://rest.mymobileapi.com/bulkmessages';

        // $authHeader = 'Authorization: Bearer ' . $authToken;

        // $sendData = '{ "messages" : [ { "content" : "Hello SMS World from PHP", "destination" : "0843682219" } ] }';

        // $options = array(
        //     'http' => array(
        //         'header'  => array("Content-Type: application/json", $authHeader),
        //         'method'  => 'POST',
        //         'content' => $sendData,
        //         'ignore_errors' => true
        //     )
        // );
        // $context  = stream_context_create($options);

        // $sendResult = file_get_contents($sendUrl, false, $context);

        // $status_line = $http_response_header[0];
        // preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
        // $status = $match[1];

        // if ($status === '200') {
        //     var_dump($sendResult);
        // } else {
        //     var_dump($sendResult);
        // }


        return view('welcome');
    }
}
