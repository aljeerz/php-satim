<?php

namespace Aljeerz\PhpSatim\Support\Http\Responses;

use Aljeerz\PhpSatim\Exceptions\SatimApiException;
use Aljeerz\PhpSatim\Exceptions\SatimHttpException;
use Aljeerz\PhpSatim\Support\Enums\SatimCurrency;
use Aljeerz\PhpSatim\Support\Enums\SatimOrderStatus;

readonly class SatimConfirmOrderResponse extends SatimCoreResponse
{
    public int $depositAmount;
    public SatimCurrency $currency;

    public int $actionCode;
    public string $actionCodeDescription;
    public string $orderNumber;
    public SatimOrderStatus $orderStatus;

    public int $amount;
    public array $params;
    public ?string $approvalCode;
    public ?string $pan;
    public ?string $expiration;

    public ?string $ip;

    public ?string $svfeResponse;

    public function __construct(array $payload)
    {
        try {
            parent::__construct(
                errorCode: $payload['ErrorCode'],
                errorMessage: $payload['ErrorMessage'] ?? 'An unknown error occurred',
            );

            if (!$this->isErrored()) {
                $this->currency = SatimCurrency::from(
                    $payload['Currency'] ?? SatimCurrency::DZD->value
                );

                $this->depositAmount = $payload['depositAmount'];
                $this->actionCode = $payload['actionCode'];
                $this->actionCodeDescription = $payload['actionCodeDescription'] ?? '';

                $this->orderNumber = $payload['OrderNumber'];
                $this->orderStatus = SatimOrderStatus::fromOrderStatusCode($payload['OrderStatus']);

                $this->amount = $payload['Amount'];

                if (isset($payload['approvalCode'])) {
                    $this->approvalCode = $payload['approvalCode'];
                }

                if (isset($payload['Pan'])) {
                    $this->pan = $payload['Pan'];
                }

                if (isset($payload['expiration'])) {
                    $this->expiration = $payload['expiration'];
                }

                if (isset($payload['Ip'])) {
                    $this->ip = $payload['Ip'];
                }

                if (isset($payload['SvfeResponse'])) {
                    $this->svfeResponse = $payload['SvfeResponse'];
                }

                if (isset($payload['params'])) {
                    $this->params = $payload['params'];
                } else {
                    $this->params = [];
                }
            } else {
                $exception = new SatimApiException($this->errorMessage, (int)$this->errorCode);
                if ($payload["actionCode"] ?? null) {
                    $exception = $exception->withActionCode(
                        $payload["actionCode"],
                        $payload["actionCodeDescription"] ?? '',
                    );
                }
                throw $exception;
            }

        } catch (\Throwable $th) {
            if ($th instanceof SatimApiException) {
                throw $th;
            }
            throw new SatimHttpException("Failed to parse Satim confirm order response: " . $th->getMessage(), 0, $th);
        }
    }

    protected function isErrored()
    {
        return $this->errorCode !== '0' && $this->errorCode !== '2';
    }
}
