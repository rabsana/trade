<?php

namespace Rabsana\Trade\Http\Controllers\AdminApi;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Http\Resources\SymbolStatus\SymbolStatusCollection;
use Rabsana\Trade\Models\SymbolOrderStatus;

class AdminApiSymbolOrderStatusController extends Controller
{

    public function index()
    {
        $statuses = SymbolOrderStatus::get();

        return (new SymbolStatusCollection($statuses))->setCustomWith([
            'message'   => Lang::get("trade::symbolOrderStatus.list")
        ]);
    }
}
