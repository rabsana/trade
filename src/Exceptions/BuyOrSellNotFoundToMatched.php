<?php

namespace Rabsana\Trade\Exceptions;

use Exception;

class BuyOrSellNotFoundToMatched extends Exception
{

    public $message;
    public $code;

    public function __construct($message = '', $code = 404)
    {
        $this->message = $message;
        $this->code = $code;
    }
}
