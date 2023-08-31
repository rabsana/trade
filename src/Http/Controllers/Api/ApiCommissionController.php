<?php

namespace Rabsana\Trade\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Rabsana\Trade\Actions\GetUserCommissionAction;
use Rabsana\Trade\Helpers\Json;

class ApiCommissionController extends Controller
{

    public function index(Request $request)
    {
        return Json::response(200, "درصد کمیسیون", app(GetUserCommissionAction::class)->run());
    }
}
