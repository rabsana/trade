<?php

namespace Rabsana\Trade\Tasks;

use Exception;
use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\Symbol;

class GetSymbolByPairTask extends Task
{
    public function run($pair = NULL)
    {
        // check the pair has passed to task or not
        if (empty($pair)) {
            throw new Exception('the pair has not passed to GetSymbolByPairTask', 500);
        }

        // get the symbol
        return Symbol::pair($pair)
            ->with('validation')
            ->with('types')
            ->firstOrFail();
    }
}
