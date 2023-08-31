<?php

namespace Rabsana\Trade\Contracts\Abstracts;

use Rabsana\Trade\Actions\GetUserCommissionAction;
use Rabsana\Trade\Contracts\Interfaces\Order as InterfacesOrder;

abstract class Order implements InterfacesOrder
{
    public function getCommissionInfo()
    {
        return app(GetUserCommissionAction::class)->run();
    }

    public function getCommissionPercent(array $commission)
    {
        return (strtoupper(request()->get('side')) == 'BUY') ? $commission['takerFee'] : $commission['makerFee'];
    }
}
