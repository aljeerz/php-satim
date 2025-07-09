<?php

namespace Aljeerz\PhpSatim\Support\Http\Responses;

abstract readonly class SatimCoreResponse
{
    public function __construct(
        public string $errorCode,
        public string $errorMessage,
    )
    {
    }

    public function isSuccessful(): bool
    {
        return $this->errorCode === '0';
    }

    abstract protected function isErrored();

}
