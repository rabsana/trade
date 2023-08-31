<?php

namespace Rabsana\Trade\Http\Controllers\Api;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Helpers\Json;
use Rabsana\Trade\Http\Resources\SymbolChart\SymbolChartCollection;
use Rabsana\Trade\Models\SymbolChart;
use Rabsana\Trade\Tasks\GetAllCandleResolutionTask;

class ApiSymbolChartController extends Controller
{
    public function index(Request $request, $symbol, $candle = '4H')
    {
        try {

            $symbolCharts = SymbolChart::orderBy('created_at', 'ASC')
                ->symbol($symbol)
                ->candle($candle)
                ->get();

            return (new SymbolChartCollection($symbolCharts))->setCustomWith([
                "message" => $symbol . " Chart"
            ]);

            // 
        } catch (ModelNotFoundException $e) {

            Log::debug("rabsana-trade-fetch-chart: " . $e);

            return Json::response(404, $symbol . " Not-found");

            // 
        } catch (Exception $e) {

            Log::debug("rabsana-trade-fetch-chart: " . $e);

            return Json::response(500, "server-error");

            // 
        }
    }

    public function candles()
    {
        try {

            $candles = app(GetAllCandleResolutionTask::class)->run();

            return Json::response(200, Lang::get("trade::symbolChart.candles"), $candles);

            // 
        } catch (Exception $e) {

            Log::debug("rabsana-trade-fetch-chart-candles: " . $e);

            return Json::response(500, "server-error");
        }
    }
}
