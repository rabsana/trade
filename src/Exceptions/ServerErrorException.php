<?php

namespace Rabsana\Trade\Exceptions;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Contracts\Abstracts\HandleException;

class ServerErrorException extends HandleException
{
    public function __construct()
    {
        $this->setStatus(500);
        $this->setMessage(Lang::get('trade::exception.serverError'));
    }
}
