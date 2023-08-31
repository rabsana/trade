<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolChart;

class SymbolChartSeededTodayTask extends Task
{
    public function run()
    {
        return (bool)(!empty(SymbolChart::todayCandles()->first()));
    }
}
