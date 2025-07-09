<?php

namespace Aljeerz\PhpSatim\Support\Http;

use Aljeerz\PhpSatim\Support\Http\Responses\SatimConfirmOrderResponse;
use Aljeerz\PhpSatim\Support\Http\Responses\SatimOrderStatusResponse;
use Aljeerz\PhpSatim\Support\Http\Responses\SatimRefundOrderResponse;
use Aljeerz\PhpSatim\Support\Http\Responses\SatimRegisterOrderResponse;
use Aljeerz\PhpSatim\Exceptions\SatimApiException;
use Aljeerz\PhpSatim\Exceptions\SatimHttpException;
use GuzzleHttp\Client;

class SatimHttpClient
{

    protected $endpoint = 'https://cib.satim.dz/payment/rest';
    protected Client $httpClient;

    public function __construct(bool $testMode = false)
    {
        if ($testMode) {
            $this->endpoint = 'https://test.satim.dz/payment/rest';
        }

        $this->httpClient = new Client();
    }

    public function registerOrderQuery(array $query): SatimRegisterOrderResponse
    {
        $response = $this->httpClient->request('GET', $this->endpoint . '/register.do', [
            'query' => $query,
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new SatimHttpException("HTTP request failed with status code: $statusCode");
        }

        $body = $response->getBody()->getContents();
        $responseData = json_decode($body, true);

        return new SatimRegisterOrderResponse($responseData);
    }

    public function confirmOrderQuery(array $query): SatimConfirmOrderResponse
    {
        $response = $this->httpClient->request('GET', $this->endpoint . '/confirmOrder.do', [
            'query' => $query,
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new SatimHttpException("HTTP request failed with status code: $statusCode");
        }

        $body = $response->getBody()->getContents();

        $responseData = json_decode($body, true);

        $response = new SatimConfirmOrderResponse($responseData);

        if (!$response->isSuccessful()) {
            throw new SatimApiException($response->errorMessage, $response->errorCode);
        }

        return $response;
    }

    public function getOrderStatusQuery(array $query): SatimOrderStatusResponse
    {
        $response = $this->httpClient->request('GET', $this->endpoint . '/getOrderStatus.do', [
            'query' => $query,
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new SatimHttpException("HTTP request failed with status code: $statusCode");
        }

        $body = $response->getBody()->getContents();

        $responseData = json_decode($body, true);

        $response = new SatimOrderStatusResponse($responseData);

        if (!$response->isSuccessful()) {
            throw new SatimApiException($response->errorMessage, $response->errorCode);
        }

        return $response;
    }

    public function refundOrderQuery(array $query): SatimRefundOrderResponse
    {
        $response = $this->httpClient->request('GET', $this->endpoint . '/refund.do', [
            'query' => $query,
        ]);

        $statusCode = $response->getStatusCode();
        if ($statusCode !== 200) {
            throw new SatimHttpException("HTTP request failed with status code: $statusCode");
        }

        $body = $response->getBody()->getContents();
        $responseData = json_decode($body, true);

        return new SatimRefundOrderResponse($responseData);
    }

}
