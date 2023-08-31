<?php

namespace Rabsana\Trade\Tasks;

use Rabsana\Trade\Contracts\Abstracts\Task;
use Rabsana\Trade\Models\CommissionCondition;

class GetAllCommissionsPropertiesTask extends Task
{
    public function run()
    {
        return array_keys(CommissionCondition::PROPERTIES);
    }
}
