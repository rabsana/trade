<?php

namespace Rabsana\Trade\Tasks\Info;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Helpers\Math;
use Rabsana\Trade\Models\SymbolOrder;

class GetChangePercentTask extends Task
{
    public function run($pair = NULL)
    {
        $lastDayPrice = optional(SymbolOrder::pair($pair)
            ->where('filled_at', '<=', date('Y-m-d H:i:s', strtotime("-1 day")))
            ->where('filled_at', '>=', date('Y-m-d H:i:s', strtotime('yesterday')))
            ->filled()
            ->latest('filled_at')
            ->latest('id')
            ->first())
            ->price ?? 0;

        $todayPrice = optional(SymbolOrder::pair($pair)
            ->where('filled_at', '<=', date('Y-m-d H:i:s', strtotime("now")))
            ->where('filled_at', '>=', date('Y-m-d H:i:s', strtotime('today')))
            ->filled()
            ->latest('filled_at')
            ->latest('id')
            ->first())
            ->price ?? 0;

        if (!$todayPrice || !$lastDayPrice) {
            return 0;
        }

        return (float) Math::subtract(
            (float) Math::divide(
                (float) Math::multiply(
                    (float) $todayPrice,
                    100
                ),
                (float) $lastDayPrice
            ),
            100
        );
    }
}
