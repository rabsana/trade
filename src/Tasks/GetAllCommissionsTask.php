<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\Commission;

class GetAllCommissionsTask extends Task
{
    public function run()
    {
        return Commission::with('conditions')
            ->has('conditions')
            ->get();
    }
}
