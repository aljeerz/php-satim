<?php

namespace Aljeerz\PhpSatim\Support\Http\Responses;

readonly class SatimRefundOrderResponse extends SatimCoreResponse
{

    public function __construct(array $payload)
    {
        parent::__construct(
            errorCode: $payload['errorCode'],
            errorMessage: $payload['errorMessage'] ?? 'An unknown error occurred',
        );

        if ($this->isErrored()) {
            throw new \Aljeerz\PhpSatim\Exceptions\SatimApiException($this->errorMessage, $this->errorCode);
        }
    }


    protected function isErrored()
    {
        return $this->errorCode !== '0';
    }
}
