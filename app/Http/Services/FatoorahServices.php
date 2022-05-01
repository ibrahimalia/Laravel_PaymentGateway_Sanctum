<?php

namespace App\Http\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class FatoorahServices{

    private $base_url;
    private $headers;
    private $request_client;

    public function __construct(Client $request_client)
    {
        $this->request_client = $request_client;
        $this->base_url=config("payment.base_url");
        $this->headers = [
            'Content-Type' => 'application/json',
            'authorization' => 'Bearer '.config("payment.token")
        ];

    }

    public function sendPayment($data){
        $response  = $this->buildRequest('/v2/SendPayment' , 'POST' , $data);
        return $response;
    }
    public function getPaymentStatus($data){

        $response  = $this->buildRequest('/v2/getPaymentStatus' , 'POST' , $data);
        return $response;

    }

    public function buildRequest($url , $method , $data=[]){

        $request = new Request($method , $this->base_url.$url , $this->headers);
        if (! $data) {
            return false;
        }
        $response = $this->request_client->send($request,[
          'json' => $data
        ]);
        $result = json_decode($response->getBody(),true);
        return $result;
    }


}
