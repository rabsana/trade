<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\Symbol;

class GetAllSymbolsTask extends Task
{
    public function run()
    {
        return Symbol::all();
    }
}
