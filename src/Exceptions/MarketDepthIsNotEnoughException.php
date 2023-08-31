<?php

namespace Rabsana\Trade\Exceptions;

use Exception;

class MarketDepthIsNotEnoughException extends Exception
{

    public $message;
    public $code;

    public function __construct($message = '', $code = 400)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
