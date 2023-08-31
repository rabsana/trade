<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;

class ResetCandlesValuesTask extends Task
{
    public function run($candles = NULL)
    {
        if (empty($candles)) {
            return;
        }

        foreach ($candles as $candle) {
            $candle->open = 0;
            $candle->high = 0;
            $candle->low = 0;
            $candle->close = 0;
            $candle->base_volume = 0;
            $candle->quote_volume = 0;
            $candle->trade_numbers = 0;
        }
    }
}
