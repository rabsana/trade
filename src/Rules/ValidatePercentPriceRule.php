<?php

namespace Rabsana\Trade\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Helpers\Math;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Tasks\GetOrderSymbolFromCacheTask;

class ValidatePercentPriceRule implements Rule
{
    public $message;
    public $symbol;
    public $symbolValidation;
    public $averageOrderPrice;
    public $upLimit;
    public $downLimit;

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
            $this->symbolValidation = $this->symbol->validation;

            $this->setAverageOrderPrice();

            $this->setUpLimit();

            $this->setDownLimit();

            if (
                ($this->upLimit > 0 && $this->downLimit > 0) &&
                (Math::greaterThan((float) $price, (float) $this->upLimit) ||
                    Math::lessThan((float)$price, (float)$this->downLimit))
            ) {
                $this->message = Lang::get("trade::symbolOrder.priceIsOutOfRange", [
                    'downLimit' => Math::numberFormat((float) $this->downLimit),
                    'upLimit' => Math::numberFormat((float) $this->upLimit)
                ]);

                return false;
            }

            // 
        } catch (Exception $e) {

            Log::debug("rabsana-trade-validating-price-in-store-request-error :" . $e);
            $this->message = Lang::get("trade::symbolOrder.priceServerError");
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

    public function setAverageOrderPrice(): ValidatePercentPriceRule
    {
        $minutes = (int)$this->symbolValidation->percent_order_price_minute;

        if ($minutes) {

            if ($this->symbolValidation->average_price_source_is_market) {

                $this->averageOrderPrice = Math::number(
                    (float) SymbolOrder::pair($this->symbol->pair)
                        ->where('filled_at', '>=', date('Y-m-d H:i:s', strtotime("-{$minutes} minutes")))
                        ->whereNotNull('filled_at')
                        ->filled()
                        ->avg('price')
                );


                // 
            } elseif (!empty(config("rabsana-trade.getSymbolAveragePriceFrom"))) {

                $taskClass = config("rabsana-trade.getSymbolAveragePriceFrom");
                $this->averageOrderPrice = (float) (new $taskClass())->run(
                    $this->symbol->base,
                    $this->symbol->quote,
                    request()->get('side'),
                    $minutes
                );
            }


            // 
        }

        // the minute is zero
        // there are not any valid order last -$minutes ago
        if (!$minutes || !$this->averageOrderPrice) {
            // get the last price

            $this->averageOrderPrice = Math::number(
                (float) optional(
                    SymbolOrder::pair($this->symbol->pair)
                        ->filled()
                        ->latest('id')
                        ->first()
                )
                    ->price
            );
        }

        return $this;
    }

    public function setUpLimit(): ValidatePercentPriceRule
    {
        $this->upLimit = Math::number(
            (float) Math::add(
                (float) $this->averageOrderPrice,
                (float) Math::divide(
                    (float) Math::multiply((float) $this->averageOrderPrice, (float)$this->symbolValidation->percent_order_price_up),
                    100
                )
            )
        );

        return $this;
    }

    public function setDownLimit(): ValidatePercentPriceRule
    {
        $this->downLimit = Math::number(
            (float) Math::subtract(
                (float) $this->averageOrderPrice,
                (float) Math::divide(
                    (float) Math::multiply((float) $this->averageOrderPrice, (float)$this->symbolValidation->percent_order_price_down),
                    100
                )
            )
        );

        $this->downLimit = ($this->downLimit < 0) ? 0 : $this->downLimit;;

        return $this;
    }
}
