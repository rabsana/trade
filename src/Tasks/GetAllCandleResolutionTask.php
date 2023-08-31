<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;

class GetAllCandleResolutionTask extends Task
{
    public function run()
    {
        return [
            '1m'        => 60,
            '3m'        => 180,
            '5m'        => 300,
            '15m'       => 900,
            '30m'       => 1800,
            '45m'       => 2700,
            '1H'        => 3600,
            '2H'        => 7200,
            '3H'        => 10800,
            '4H'        => 14400,
            '6H'        => 21600,
            '8H'        => 28800,
            '12H'       => 43200,
            '1D'        => 86400,
            '3D'        => 259200,
            '1W'        => 604800,
            '1M'        => 2628000,
            '1Y'        => 31540000,
            '2Y'        => 63070000,
            '3Y'        => 94610000,
            '4Y'        => 126100000
        ];
    }
}
