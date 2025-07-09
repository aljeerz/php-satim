<?php

namespace Aljeerz\PhpSatim\Support\Http\Responses;

use Aljeerz\PhpSatim\Exceptions\SatimApiException;
use Aljeerz\PhpSatim\Exceptions\SatimHttpException;

readonly class SatimRegisterOrderResponse extends SatimCoreResponse
{

    public string $orderId;
    public string $formUrl;

    public function __construct(
        array $payload,
    )
    {
        parent::__construct(
            errorCode: $payload['errorCode'],
            errorMessage: $payload['errorMessage'] ?? 'An unknown error occurred',
        );

        if ($this->isErrored()) {
            throw new SatimApiException($this->errorMessage, $this->errorCode);
        }

        try {
            $this->orderId = $payload['orderId'];
            $this->formUrl = $payload['formUrl'];
        } catch (\Throwable $th) {
            if ($th instanceof SatimApiException) {
                throw $th;
            }
            throw new SatimHttpException('Failed to parse Satim register order response: ' . $th->getMessage(), 0, $th);
        }
    }

    protected function isErrored(): bool
    {
        return $this->errorCode !== '0';
    }
}
