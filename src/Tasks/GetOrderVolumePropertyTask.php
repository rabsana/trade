<?php

namespace Rabsana\Trade\Tasks;

use Exception;
use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolOrder;

class GetOrderVolumePropertyTask extends Task
{
    public function run($commission = [], $condition = [], $user = null)
    {
        try {

            $quote = $commission['symbol_quote'];
            $period = $condition['period'];

            //
            if (empty($quote) || empty($period)) {
                throw new Exception("quote and period have not provided.");
            }

            // get the user's order quote volume with quote and period
            $orderVolume = SymbolOrder::filled()
                ->quote($quote)
                ->orderableFilter(!empty($user) ? get_class($user) : NULL, optional($user)->id)
                ->where('filled_at', '>=', date('Y-m-d H:i:s', strtotime("- $period minutes")))
                ->sum('original_quote_qty');

            return $orderVolume;
            // 
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
