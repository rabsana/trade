<?php

namespace Rabsana\Trade\Rules\Symbol;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;

class ScaleNotional
{
    public function __construct($scaleNotional, $value)
    {
        if (Math::lessThan((float)$scaleNotional, (float)Math::decimalPlaceNumber((float)$value))) {

            throw new SymbolOrderValidationException(
                Lang::get("trade::symbolOrder.scaleNotional", [
                    'notional' => Math::numberFormat((float)$value),
                    'roundedNotional' => Math::numberFormat((float)number_format($value, $scaleNotional, '.', ''))
                ])
            );

            // 
        }
    }
}
