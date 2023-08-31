<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Helpers\Math;

class CreateUpdateNotDailyCandlesArrayTask extends Task
{
    public function run($notDailyCandles = NULL, $dailyCandles = NULL)
    {
        $updateCandles = [];

        if (empty($notDailyCandles) || empty($dailyCandles)) {
            return $updateCandles;
        }

        // reset not daily candles
        app(ResetCandlesValuesTask::class)->run($notDailyCandles);

        // change candles values
        foreach ($notDailyCandles as $notDaily) {

            foreach ($dailyCandles as $daily) {

                // check the symbol and candle resolution
                if (
                    $daily->symbol_id == $notDaily->symbol_id &&
                    $daily->open_time >= $notDaily->open_time &&
                    $daily->close_time <= $notDaily->close_time
                ) {

                    if ($notDaily->open == 0 && $notDaily->high == 0 && $notDaily->low == 0 && $notDaily->close == 0) {
                        // its the first time
                        $notDaily->open = $daily->open;
                        $notDaily->high = $daily->high;
                        $notDaily->low = $daily->low;
                        $notDaily->close = $daily->close;

                        // 
                    } elseif (Math::greaterThan((float)$daily->high, (float)$notDaily->high)) {
                        // bullish candle
                        $notDaily->high = $daily->high;
                        $notDaily->close = $daily->high;

                        // 
                    } elseif (Math::lessThan((float) $daily->low, (float)$notDaily->low)) {
                        // bearish candle
                        $notDaily->low = $daily->low;
                        $notDaily->close = $daily->low;

                        // 
                    } else {

                        // at the body of candle
                        $notDaily->close = $daily->close;

                        // 
                    }

                    $notDaily->base_volume = Math::add((float) $notDaily->base_volume, (float) $daily->base_volume);
                    $notDaily->quote_volume = Math::add((float) $notDaily->quote_volume, (float) $daily->quote_volume);
                    $notDaily->trade_numbers = Math::add((float) $notDaily->trade_numbers, (float) $daily->trade_numbers);
                    $notDaily->modified = 1;
                }
            }
        }

        // create update candles
        $updateCandles = app(CreateUpdateCandlesArrayTask::class)->run($notDailyCandles);

        return $updateCandles;
    }
}
