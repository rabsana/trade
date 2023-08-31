<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolChart;

class GetDailyCandlesByOpenAndCloseTimeTask extends Task
{
    public function run($openTime = NULL, $closeTime = NULL)
    {
        if (empty($openTime) || empty($closeTime)) {
            return;
        }

        return SymbolChart::candle('1D')
            ->where('open_time', '>=', $openTime)
            ->where('close_time', '<=', $closeTime)
            ->get();
    }
}
