<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolOrder;

class GetTakersOrderTask extends Task
{
    public function run($symbol = 'BTCUSDT', $limit = 30)
    {
        return SymbolOrder::side('buy')
            ->pair($symbol)
            ->tradeable()
            ->orderBy('price', 'DESC')
            ->orderBy('created_at', 'ASC')
            ->orderBy('id', 'DESC')
            ->take($limit)
            ->get();
    }
}
