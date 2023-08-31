<?php

namespace Rabsana\Trade\Rules\Symbol;

use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;

class ScaleQty
{
    public function __construct($scaleQty, $value)
    {
        if (Math::lessThan((float)$scaleQty, (float)Math::decimalPlaceNumber((float)$value))) {

            throw new SymbolOrderValidationException(
                Lang::get("trade::symbolOrder.scaleQty", [
                    'qty' => Math::numberFormat((float)$value),
                    'roundedQty' => Math::numberFormat((float)number_format($value, $scaleQty, '.', ''))
                ])
            );

            // 
        }
    }
}
