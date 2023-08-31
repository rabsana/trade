<?php

namespace Rabsana\Trade\Actions;

use Rabsana\Trade\Contracts\Abstracts\Action;

class UpdateSymbolChartAction extends Action
{
    public function run()
    {
        // update today candles base on today filled orders
        app(UpdateTodayCandlesAction::class)->run();

        // update not daily candles like 3D,1W,1M... base on previous daily candles
        app(UpdateNotDailyCandlesAction::class)->run();
    }
}
