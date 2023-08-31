<?php

namespace Rabsana\Trade\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Tasks\GetOrderSymbolFromCacheTask;

class CheckSideIsActiveRule implements Rule
{
    public $message;
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

            $symbol = app(GetOrderSymbolFromCacheTask::class)->run();
            $value = strtolower($value);

            if (!(bool)$symbol->{$value . "_is_active"}) {
                $this->message = Lang::get("trade::symbolOrder.sideIsNotActive", ['side' => Lang::get("trade::symbolOrder.$value")]);
                return false;
            }

            // 
        } catch (Exception $e) {

            Log::debug("rabsana-trade-validating-side-in-store-request-error :" . $e);
            $this->message = Lang::get("trade::symbolOrder.sideServerError");

            return false;
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
