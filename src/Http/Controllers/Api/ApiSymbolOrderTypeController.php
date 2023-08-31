<?php

namespace Rabsana\Trade\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Http\Resources\SymbolType\SymbolTypeCollection;
use Rabsana\Trade\Models\SymbolOrderType;

class ApiSymbolOrderTypeController extends Controller
{

    public function index()
    {
        $types = SymbolOrderType::get();
        
        return (new SymbolTypeCollection($types))->setCustomWith([
            'message'   => Lang::get("trade::symbolOrderType.list")
        ]);
    }
}
