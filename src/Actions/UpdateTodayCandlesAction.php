<?php

namespace Rabsana\Trade\Actions;

use Rabsana\Trade\Contracts\Abstracts\Action;
use Rabsana\Trade\Tasks\CreateUpdateTodayCandlesArrayTask;
use Rabsana\Trade\Tasks\GetTodayCandlesTask;
use Rabsana\Trade\Tasks\GetTodayFilledOrdersTask;
use Rabsana\Trade\Tasks\UpdateSymbolChartByIdTask;

class UpdateTodayCandlesAction extends Action
{

    public function run()
    {
        $todayOrders = app(GetTodayFilledOrdersTask::class)->run();
        $todayCandles = app(GetTodayCandlesTask::class)->run();
        $updateCandles = app(CreateUpdateTodayCandlesArrayTask::class)->run($todayOrders, $todayCandles);
        app(UpdateSymbolChartByIdTask::class)->run($updateCandles);
    }
}
