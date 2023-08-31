<?php

namespace Rabsana\Trade\Rules\Symbol;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;

class ScalePrice
{
    public function __construct($scalePrice, $value)
    {
        if (Math::lessThan((float)$scalePrice, (float)Math::decimalPlaceNumber((float)$value))) {

            throw new SymbolOrderValidationException(
                Lang::get("trade::symbolOrder.scalePrice", [
                    'price' => Math::numberFormat((float)$value),
                    'roundedPrice' => Math::numberFormat((float)number_format($value, $scalePrice, '.', ''))
                ])
            );

            // 
        }
    }
}
