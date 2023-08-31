<?php

namespace Rabsana\Trade\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Helpers\Math;
use Rabsana\Trade\Rules\Symbol\MaxNotional;
use Rabsana\Trade\Rules\Symbol\MinNotional;
use Rabsana\Trade\Rules\Symbol\ScaleNotional;
use Rabsana\Trade\Tasks\GetOrderSymbolFromCacheTask;

class ValidateNotionalRule implements Rule
{
    public $message;
    public $symbol;

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $price
     * @return bool
     */
    public function passes($attribute, $price)
    {
        try {

            $this->symbol = app(GetOrderSymbolFromCacheTask::class)->run();
            $notional = Math::multiply((float)$price, (float)request()->get('base_qty'));

            new MinNotional(
                $this->symbol->validation->min_notional,
                $notional
            );

            new MaxNotional(
                $this->symbol->validation->max_notional,
                $notional
            );

            new ScaleNotional(
                $this->symbol->validation->scale_notional,
                $notional
            );


            // 
        } catch (SymbolOrderValidationException $e) {

            $this->message = $e->getMessage();
            return false;

            // 
        } catch (Exception $e) {

            Log::debug("rabsana-trade-validating-notional-in-store-request-error :" . $e);
            $this->message = Lang::get("trade::symbolOrder.notionalServerError");
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
