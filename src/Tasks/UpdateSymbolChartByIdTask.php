<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Mavinoo\Batch\Batch;
use Illuminate\Database\DatabaseManager;
use Rabsana\Trade\Models\SymbolChart;

class UpdateSymbolChartByIdTask extends Task
{
    public function run($update = [])
    {
        if (empty($update)) {
            return;
        }

        foreach (array_chunk($update, 100) as $item) {
            (new Batch(app()->make(DatabaseManager::class)))->update(new SymbolChart(), $item, 'id');
        }
    }
}
