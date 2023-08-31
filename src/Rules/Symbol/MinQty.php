<?php

namespace Rabsana\Trade\Rules\Symbol;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;

class MinQty
{
    public function __construct($minQty, $value)
    {
        if (Math::greaterThan((float)$minQty, (float)$value)) {

            throw new SymbolOrderValidationException(Lang::get("trade::symbolOrder.minQty", ['minQty' => Math::numberFormat((float)$minQty)]));

            // 
        }
    }
}
