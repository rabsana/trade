<?php

namespace Rabsana\Trade\Http\Controllers\AdminApi;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Http\Resources\SymbolType\SymbolTypeCollection;
use Rabsana\Trade\Models\SymbolOrderType;

class AdminApiSymbolOrderTypeController extends Controller
{

    public function index()
    {
        $types = SymbolOrderType::get();
        
        return (new SymbolTypeCollection($types))->setCustomWith([
            'message'   => Lang::get("trade::symbolOrderType.list")
        ]);
    }
}
