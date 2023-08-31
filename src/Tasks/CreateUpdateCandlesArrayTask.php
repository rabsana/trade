<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;

class CreateUpdateCandlesArrayTask extends Task
{
    public function run($candles = NULL)
    {
        $updateCandles = [];
        if (empty($candles)) {
            return $updateCandles;
        }

        foreach ($candles as $candle) {
            if ($candle->modified) {
                $updateCandles[] = [
                    'id'                    => $candle->id,
                    'open'                  => $candle->open,
                    'high'                  => $candle->high,
                    'low'                   => $candle->low,
                    'close'                 => $candle->close,
                    'base_volume'           => $candle->base_volume,
                    'quote_volume'          => $candle->quote_volume,
                    'trade_numbers'         => $candle->trade_numbers,
                ];
            }
        }

        return $updateCandles;
    }
}
