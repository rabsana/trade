<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Helpers\Math;

class CreateUpdateTodayCandlesArrayTask extends Task
{
    public function run($orders = null, $candles = null)
    {
        $updateCandles = [];

        if (empty($orders) || empty($candles)) {
            return $updateCandles;
        }

        // reset today candles
        app(ResetCandlesValuesTask::class)->run($candles);

        // change candles values
        foreach ($orders as $order) {

            $filledTime = strtotime($order->filled_at);

            foreach ($candles as $candle) {

                // check the symbol and candle resolution
                if (
                    $candle->symbol->pair_lower_case == $order->pair_lower_case &&
                    $filledTime >= $candle->open_time &&
                    $filledTime <= $candle->close_time
                ) {


                    if ($candle->open == 0 && $candle->high == 0 && $candle->low == 0 && $candle->close == 0) {
                        // its the first time
                        $candle->open = $order->price;
                        $candle->high = $order->price;
                        $candle->low = $order->price;
                        $candle->close = $order->price;

                        // 
                    } elseif (Math::greaterThan((float)$order->price, (float)$candle->high)) {
                        // bullish candle
                        $candle->high = $order->price;
                        $candle->close = $order->price;

                        // 
                    } elseif (Math::lessThan((float) $order->price, (float)$candle->low)) {
                        // bearish candle
                        $candle->low = $order->price;
                        $candle->close = $order->price;

                        // 
                    } else {

                        // at the body of candle
                        $candle->close = $order->price;

                        // 
                    }

                    $candle->base_volume = Math::add((float) $candle->base_volume, (float) $order->base_qty);
                    $candle->quote_volume = Math::add((float) $candle->quote_volume, (float) $order->quote_qty);
                    $candle->trade_numbers = $candle->trade_numbers + 1;
                    $candle->modified = 1;

                    // 
                }
            }
        }

        // create update candles
        $updateCandles = app(CreateUpdateCandlesArrayTask::class)->run($candles);

        return $updateCandles;
    }
}
