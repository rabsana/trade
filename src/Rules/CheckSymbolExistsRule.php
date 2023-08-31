<?php

namespace Rabsana\Trade\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Tasks\GetSymbolByPairTask;

class CheckSymbolExistsRule implements Rule
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

            // try to find the symbol with pair scope
            $symbol = app(GetSymbolByPairTask::class)->run($value);

            // after fetching the symbol save it in config
            Config::set('orderSymbol', $symbol);

            // 
        } catch (ModelNotFoundException $e) {

            Log::debug("rabsana-trade-validating-symbol-in-store-request-error :" . $e);
            $this->message = Lang::get("trade::symbolOrder.symbolDoesNotExist", ['symbol' => strtoupper($value)]);

            return false;

            // 
        } catch (Exception $e) {

            Log::debug("rabsana-trade-validating-symbol-in-store-request-error :" . $e);
            $this->message = Lang::get("trade::symbolOrder.symbolServerError");

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
