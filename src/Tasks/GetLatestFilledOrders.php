<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolOrder;

class GetLatestFilledOrders extends Task
{
    public function run($symbol = 'BTCUSDT', $limit = 30)
    {
        return SymbolOrder::latest('updated_at')
            ->pair($symbol)
            ->filled()
            ->take($limit)
            ->get();
    }
}
