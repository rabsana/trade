<?php

namespace Rabsana\Trade\Tasks\Info;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolOrder;

class GetLastDayHighTask extends Task
{
    public function run($pair = NULL)
    {
        return (float) SymbolOrder::pair($pair)
            ->filled()
            ->whereDate('filled_at', date('Y-m-d', strtotime('yesterday')))
            ->max('price');
    }
}
