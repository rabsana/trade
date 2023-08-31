<?php

namespace Rabsana\Trade\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Rules\Symbol\MaxPrice;
use Rabsana\Trade\Rules\Symbol\MinPrice;
use Rabsana\Trade\Rules\Symbol\ScalePrice;
use Rabsana\Trade\Tasks\GetOrderSymbolFromCacheTask;

class ValidatePriceRule implements Rule
{
    public $message;
    public $symbol;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {

            $this->symbol = app(GetOrderSymbolFromCacheTask::class)->run();

            new MinPrice(
                $this->symbol->validation->min_price,
                $value
            );

            new MaxPrice(
                $this->symbol->validation->max_price,
                $value
            );

            new ScalePrice(
                $this->symbol->validation->scale_price,
                $value
            );


            // 
        } catch (SymbolOrderValidationException $e) {

            $this->message = $e->getMessage();
            return false;

            // 
        } catch (Exception $e) {

            Log::debug("rabsana-trade-validating-price-in-store-request-error :" . $e);
            $this->message = Lang::get("trade::symbolOrder.priceServerError");
            return false;

            // 
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
