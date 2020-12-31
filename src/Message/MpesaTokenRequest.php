<?php
/**
 * Mpesa Token Request
 */

namespace Omnipay\Mpesa\Message;

class MpesaTokenRequest extends AbstractRequest
{
    public function getData()
    {
        return array('grant_type' => 'client_credentials');
    }

    protected function getEndpoint()
    {
        return 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    }

    public function sendData($data)
    {
		$body = $data ? http_build_query($data, '', '&') : null;
		// dd($this->httpClient);
        $httpResponse = $this->httpClient->get(
            
            $this->getEndpoint(),
            array(
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode("{$this->getConsumerKey()}:{$this->getConsumerSecret()}"),
            ),
            
        )->send();
		// Empty response body should be parsed also as and empty array
		// dd($httpResponse);
		// echo "<pre>";
		// var_dump($httpResponse);
		// die();
        $body = (string) $httpResponse->getBody();
        $jsonToArrayResponse = !empty($body) ? json_decode($body, true) : array();
        return $this->response = new MpesaResponse($this, $jsonToArrayResponse, $httpResponse->getStatusCode());
    }

}
