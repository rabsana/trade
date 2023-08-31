<?php

namespace Rabsana\Trade\Tasks\Info;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolOrder;

class GetPriceTask extends Task
{
    public function run($pair = NULL)
    {
        return optional(SymbolOrder::pair($pair)
            ->filled()
            ->latest('filled_at')
            ->latest('id')
            ->first())
            ->price ?? 0;
    }
}
