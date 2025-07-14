<?php

namespace Aljeerz\PhpSatim\Exceptions;

use Aljeerz\PhpSatim\Support\Enums\SatimOrderStatus;
use Exception;

class SatimException extends Exception
{
    protected $actionCode;
    protected $actionCodeDescription;
    protected $jsonRespCode;
    protected $jsonRespCodeDesc;

    protected ?SatimOrderStatus $orderStatus = null;


    public function withActionCode($actionCode, $actionCodeDescription = "")
    {
        $this->actionCode = $actionCode;
        $this->actionCodeDescription = $actionCodeDescription;

        return $this;
    }

    public function getActionCode()
    {
        return $this->actionCode;
    }

    public function getActionCodeDescription()
    {
        return $this->actionCodeDescription;
    }

    public function withJsonRespCode($respCode, $respCodeDesc = "")
    {
        $this->jsonRespCode = $respCode;
        $this->jsonRespCodeDesc = $respCodeDesc;

        return $this;
    }

    public function getJsonRespCode()
    {
        return $this->jsonRespCode;
    }

    public function getJsonRespCodeDesc()
    {
        return $this->jsonRespCodeDesc;
    }

    public function withOrderStatus(SatimOrderStatus $orderStatus)
    {
        $this->orderStatus = $orderStatus;
        return $this;
    }

    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

}
