<?php

namespace Rabsana\Trade\Tasks;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\SymbolChart;

class InsertSymbolChartTask extends Task
{

    public function run($symbolCharts = [])
    {
        DB::beginTransaction();
        try {

            if (!empty($symbolCharts)) {
                foreach (array_chunk($symbolCharts, 500) as $symbolChart) {
                    SymbolChart::insert($symbolChart);
                }
            }

            // everything is fine here
            DB::commit();

            // 
        } catch (Exception $e) {

            // there is a problem
            DB::rollBack();

            Log::debug("rabsana-trade-error-inserting-symbol-chart :" . $e);
        }
    }
}
