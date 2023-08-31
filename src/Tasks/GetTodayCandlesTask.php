<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolChart;

class GetTodayCandlesTask extends Task
{
    public function run()
    {
        return SymbolChart::todayCandles()
            ->with('symbol')
            ->get();
    }
}
