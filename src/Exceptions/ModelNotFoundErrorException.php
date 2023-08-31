<?php

namespace Rabsana\Trade\Exceptions;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Contracts\Abstracts\HandleException;

class ModelNotFoundErrorException extends HandleException
{
    public function __construct()
    {
        $this->setStatus(404);
        $this->setMessage(Lang::get('trade::exception.modelNotFoundError'));
    }
}
