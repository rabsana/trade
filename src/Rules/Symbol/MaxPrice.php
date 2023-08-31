<?php

namespace Rabsana\Trade\Rules\Symbol;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;

class MaxPrice
{
    public function __construct($maxPrice, $value)
    {
        if (Math::lessThan((float)$maxPrice, (float)$value)) {

            throw new SymbolOrderValidationException(Lang::get("trade::symbolOrder.maxPrice", ['maxPrice' => Math::numberFormat((float)$maxPrice)]));

            // 
        }
    }
}
