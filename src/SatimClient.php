<?php

namespace Aljeerz\PhpSatim;

use Aljeerz\PhpSatim\Support\Http\Responses\SatimConfirmOrderResponse;
use Aljeerz\PhpSatim\Support\Http\Responses\SatimRegisterOrderResponse;
use Aljeerz\PhpSatim\Support\Http\SatimHttpClient;
use Aljeerz\PhpSatim\Support\Enums\SatimLanguage;

class SatimClient
{
    protected SatimHttpClient $httpClient;

    public function __construct(
        protected string $username,
        protected string $password,
        protected string $terminalId,
        bool             $testMode,
    )
    {
        $this->httpClient = new SatimHttpClient(testMode: $testMode);
    }

    public function registerOrder(SatimOrderBuilder $builder): SatimRegisterOrderResponse
    {
        $builderData = $builder->generateOrderDetails();
        $builderData['userDefinedFields']['force_terminal_id'] = $this->terminalId;

        $data = [
            'userName' => $this->username,
            'password' => $this->password,
            ...$builderData['data'],
            'jsonParams' => json_encode($builderData['userDefinedFields']),
        ];

        return $this->httpClient->registerOrderQuery($data);
    }

    public function getOrderStatus(string $orderId)
    {
        return $this->httpClient->getOrderStatusQuery([
            'userName' => $this->username,
            'password' => $this->password,
            'orderId' => $orderId,
        ]);
    }

    public function confirmOrder(string $orderId, SatimLanguage $satimLanguage): SatimConfirmOrderResponse
    {
        return $this->httpClient->confirmOrderQuery([
            'userName' => $this->username,
            'password' => $this->password,
            'orderId' => $orderId,
            'language' => $satimLanguage->value,
        ]);
    }

    public function refundOrder(string $orderId, int $amount)
    {
        return $this->httpClient->refundOrderQuery([
            'userName' => $this->username,
            'password' => $this->password,
            'orderId' => $orderId,
            'amount' => $amount,
        ]);
    }

    public function initNewOrder() : SatimOrderBuilder
    {
        return new SatimOrderBuilder();
    }
}
