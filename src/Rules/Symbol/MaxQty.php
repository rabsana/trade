<?php

namespace Rabsana\Trade\Rules\Symbol;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;

class MaxQty
{
    public function __construct($maxQty, $value)
    {
        if (Math::lessThan((float)$maxQty, (float)$value)) {

            throw new SymbolOrderValidationException(Lang::get("trade::symbolOrder.maxQty", ['maxQty' => Math::numberFormat((float)$maxQty)]));

            // 
        }
    }
}
