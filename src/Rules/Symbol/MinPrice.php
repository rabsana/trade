<?php

namespace Rabsana\Trade\Rules\Symbol;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;

class MinPrice
{
    public function __construct($minPrice, $value)
    {
        if (Math::greaterThan((float)$minPrice, (float)$value)) {

            throw new SymbolOrderValidationException(Lang::get("trade::symbolOrder.minPrice", ['minPrice' => Math::numberFormat((float)$minPrice)]));

            // 
        }
    }
}
