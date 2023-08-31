<?php

namespace Rabsana\Trade\Exceptions;

use Exception;

class SymbolOrderValidationException extends Exception
{

    public $message;
    public $code;

    public function __construct($message = '', $code = 422)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
