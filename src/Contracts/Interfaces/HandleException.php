<?php

namespace Rabsana\Trade\Contracts\Interfaces;

use Exception;

interface HandleException
{
    public function response();

    public function report(Exception $e);
}
