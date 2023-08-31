<?php

namespace Rabsana\Trade\Rules\Symbol;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;

class MinNotional
{
    public function __construct($minNotional, $value)
    {
        if (Math::greaterThan((float)$minNotional, (float)$value)) {

            throw new SymbolOrderValidationException(Lang::get("trade::symbolOrder.minNotional", ['minNotional' => Math::numberFormat((float)$minNotional)]));

            // 
        }
    }
}
