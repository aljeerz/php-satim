<?php

namespace Aljeerz\PhpSatim\Support;

use Aljeerz\PhpSatim\Exceptions\SatimException;
use Aljeerz\PhpSatim\Support\Enums\SatimCurrency;
use Aljeerz\PhpSatim\Support\Enums\SatimLanguage;

class SatimOrderBuilder
{
    protected ?string $orderNumber = null;
    protected ?int $amount = null;
    protected SatimCurrency $currency = SatimCurrency::DZD;
    protected SatimLanguage $language = SatimLanguage::FR;
    protected ?string $returnUrl = null;
    protected ?string $failUrl = null;
    protected ?string $description = null;
    protected array $userDefinedFields = [];

    public function __construct()
    {
    }

    public function withOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    public function withAmount(int $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function withCurrency(SatimCurrency $currency): self
    {
        $this->currency = $currency;
        return $this;
    }

    public function withLanguage(SatimLanguage $language): self
    {
        $this->language = $language;
        return $this;
    }

    public function withReturnUrl(string $returnUrl): self
    {
        $this->returnUrl = $returnUrl;
        return $this;
    }

    public function withFailUrl(string $failUrl): self
    {
        $this->failUrl = $failUrl;
        return $this;
    }

    public function withDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @throws SatimException
     */
    public function withUserDefinedFields(array $fields): self
    {
        if (sizeof($this->userDefinedFields) > 5) {
            throw new SatimException('You can only set up to 5 user defined fields.');
        }

        $index = 1;
        foreach ($fields as $key => $value) {
            if (!is_string($key) || !is_string($value)) {
                throw new SatimException('User defined fields must be key-value pairs of strings.');
            }
            $this->userDefinedFields['udf' . $index] = $value;
        }

        $index = 1;
        foreach ($fields as $key => $value) {
            $this->userDefinedFields['udf' . $index . '_key'] = $key;
        }

        return $this;
    }

    /**
     * @return array{data: array{amount: int, currency: string, language: string, orderNumber: string, returnUrl: string, failUrl?: string, description?: string}, userDefinedFields: array}
     * @throws SatimException
     */
    public function generateOrderDetails()
    {

        $this->validate();

        $finalData = [
            'amount' => $this->amount,
            'currency' => $this->currency->value,
            'language' => $this->language->value,
            'returnUrl' => $this->returnUrl,
            'orderNumber' => $this->orderNumber,
        ];

        if ($this->failUrl) {
            $finalData['failUrl'] = $this->failUrl;
        }

        if ($this->description) {
            $finalData['description'] = $this->description;
        }

        return [
            'data' => $finalData,
            'userDefinedFields' => $this->userDefinedFields,
        ];
    }

    /**
     * @throws SatimException
     */
    private function validate()
    {
        if (!$this->orderNumber) {
            throw new SatimException('Order number is required.');
        }

        if (!$this->amount || $this->amount < 5000) {
            throw new SatimException('Amount must be at least 50 DZD.');
        }

        if (!$this->returnUrl) {
            throw new SatimException('Return URL is required.');
        }

        foreach ($this->userDefinedFields as $key => $value) {
            if (strlen($key) > 20 || strlen($value) > 20) {
                throw new SatimException('User defined fields must not exceed 20 characters.');
            }
        }
    }
}
