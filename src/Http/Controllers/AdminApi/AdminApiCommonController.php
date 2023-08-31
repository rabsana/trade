<?php

namespace Rabsana\Trade\Http\Controllers\AdminApi;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rabsana\Trade\Helpers\Json;
use Rabsana\Trade\Models\CommissionCondition;
use Rabsana\Trade\Models\Symbol;

class AdminApiCommonController extends Controller
{

    public function symbolQuotes(Request $request)
    {
        $quotes = Symbol::select('quote')
            ->groupBy('quote')
            ->get();

        return Json::response(200, '', collect($quotes)->pluck('quote')->toArray());
    }

    public function commissionProperties(Request $request)
    {
        return Json::response(200, '', collect(CommissionCondition::PROPERTIES)->toArray());
    }

    public function commissionOperators(Request $request)
    {
        return Json::response(200, '', CommissionCondition::OPERATORS);
    }
}
