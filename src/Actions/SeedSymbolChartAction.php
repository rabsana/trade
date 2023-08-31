<?php

namespace Rabsana\Trade\Actions;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Contracts\Abstracts\Action;
use Rabsana\Trade\Helpers\Math;
use Rabsana\Trade\Tasks\GetAllCandleResolutionTask;
use Rabsana\Trade\Tasks\GetAllSymbolsTask;
use Rabsana\Trade\Tasks\InsertSymbolChartTask;
use Rabsana\Trade\Tasks\SymbolChartSeededTodayTask;

class SeedSymbolChartAction extends Action
{
    public function run()
    {
        DB::beginTransaction();

        try {
            // check the data seeded today or not
            if (!app(SymbolChartSeededTodayTask::class)->run()) {


                // get the symbols and candles
                $symbols = app(GetAllSymbolsTask::class)->run();
                $candles = app(GetAllCandleResolutionTask::class)->run();

                if (!empty($symbols) && !empty($candles)) {


                    $today = strtotime('today');
                    $aDaySeconds = 86400;
                    $symbolCharts = [];

                    // build the symbol charts array
                    foreach ($symbols as $symbol) {

                        foreach ($candles as $key => $candle) {

                            // count = number of rows that should be created for each candle
                            // if resolution is more than aDaySeconds we should create one row otherwise use divide to get the number rows
                            // 1m = 1440 rows, 4H = 6 rows, 1D,1W,1M = 1 row
                            $count = (Math::greaterThanOrEqual((float)$candle, (float)$aDaySeconds)) ? 1 : Math::divide((float)$aDaySeconds, (float)$candle);

                            for ($i = 0; $i < $count; $i++) {

                                $openTime = Math::add((float) $today, (float) Math::multiply((float)$i, (float)$candle));
                                $closeTime = Math::add((float) $openTime, (float) $candle);

                                $symbolCharts[] = [
                                    'symbol_id'     => $symbol->id,
                                    'candle'        => $key,
                                    'open_time'     => $openTime,
                                    'close_time'    => $closeTime,
                                    'created_at'    => now(),
                                ];
                            }
                        }
                    }

                    // insert the array to database
                    app(InsertSymbolChartTask::class)->run($symbolCharts);
                }
            }

            DB::commit();


            // 
        } catch (Exception $e) {

            DB::rollBack();

            Log::debug("rabsana-trade-seed-symbol-chart-error" . $e);

            // 
        }
    }
}
