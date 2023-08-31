<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolOrder;

class GetTodayFilledOrdersTask extends Task
{
    public function run()
    {
        return SymbolOrder::filled()
            ->whereDate('filled_at', date('Y-m-d', strtotime('today')))
            ->whereNotNull('filled_at')
            ->get();
    }
}
