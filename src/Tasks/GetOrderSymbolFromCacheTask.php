<?php

namespace Rabsana\Trade\Tasks;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Contracts\Abstracts\Task;

class GetOrderSymbolFromCacheTask extends Task
{
    public function run()
    {
        $symbol = Config::get('orderSymbol');

        if (empty($symbol)) {
            throw new Exception(Lang::get("trade::symbolOrder.symbolIsRequired"), 500);
        }

        return $symbol;
    }
}
