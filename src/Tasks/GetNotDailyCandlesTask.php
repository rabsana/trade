<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolChart;

class GetNotDailyCandlesTask extends Task
{
    public function run()
    {
        $aDaySeconds = 86400;
        return SymbolChart::whereRaw("close_time - open_time > ?", [$aDaySeconds])
            ->where('close_time', '>=', strtotime('now'))
            ->get();
    }
}
