<?php

namespace Rabsana\Trade\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Exceptions\SymbolOrderValidationException;
use Rabsana\Trade\Rules\Symbol\MaxQty;
use Rabsana\Trade\Rules\Symbol\MinQty;
use Rabsana\Trade\Rules\Symbol\ScaleQty;
use Rabsana\Trade\Tasks\GetOrderSymbolFromCacheTask;

class ValidateBaseQtyRule implements Rule
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

            new MinQty(
                $this->symbol->validation->min_qty,
                $value
            );

            new MaxQty(
                $this->symbol->validation->max_qty,
                $value
            );

            new ScaleQty(
                $this->symbol->validation->scale_qty,
                $value
            );

            // 
        } catch (SymbolOrderValidationException $e) {

            $this->message = $e->getMessage();
            return false;

            // 
        } catch (Exception $e) {

            Log::debug("rabsana-trade-validating-base-qty-in-store-request-error :" . $e);
            $this->message = Lang::get("trade::symbolOrder.baseQtyServerError");
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
