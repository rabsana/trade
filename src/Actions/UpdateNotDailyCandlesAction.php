<?php

namespace Rabsana\Trade\Actions;

use Rabsana\Trade\Contracts\Abstracts\Action;
use Rabsana\Trade\Tasks\CreateUpdateNotDailyCandlesArrayTask;
use Rabsana\Trade\Tasks\GetDailyCandlesByOpenAndCloseTimeTask;
use Rabsana\Trade\Tasks\GetNotDailyCandlesTask;
use Rabsana\Trade\Tasks\UpdateSymbolChartByIdTask;

class UpdateNotDailyCandlesAction extends Action
{
    public function run()
    {
        $notDailyCandles = app(GetNotDailyCandlesTask::class)->run();

        $dailyCandles = app(GetDailyCandlesByOpenAndCloseTimeTask::class)->run(
            collect($notDailyCandles)->min('open_time'),
            collect($notDailyCandles)->min('close_time')
        );

        $updateCandles = app(CreateUpdateNotDailyCandlesArrayTask::class)->run($notDailyCandles, $dailyCandles);
        app(UpdateSymbolChartByIdTask::class)->run($updateCandles);
    }
}
