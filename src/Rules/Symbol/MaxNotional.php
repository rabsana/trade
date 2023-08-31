<?php

namespace Rabsana\Trade\Rules\Symbol;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;

class MaxNotional
{
    public function __construct($maxNotional, $value)
    {
        if (Math::lessThan((float)$maxNotional, (float)$value)) {

            throw new SymbolOrderValidationException(Lang::get("trade::symbolOrder.maxNotional", ['maxNotional' => Math::numberFormat((float)$maxNotional)]));

            // 
        }
    }
}
