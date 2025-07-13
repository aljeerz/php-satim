<?php

namespace Aljeerz\PhpSatim\Exceptions;

use Exception;

class SatimException extends Exception
{
    protected $actionCode;
    protected $actionCodeDescription;

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

}
