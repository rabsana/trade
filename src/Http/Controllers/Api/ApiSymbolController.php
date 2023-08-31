<?php

namespace Rabsana\Trade\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Exceptions\ServerErrorException;
use Rabsana\Trade\Helpers\Json;
use Rabsana\Trade\Http\Resources\Symbol\SymbolCollection;
use Rabsana\Trade\Http\Resources\SymbolInfo\SymbolInfoResource;
use Rabsana\Trade\Models\Symbol;

class ApiSymbolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $symbols = Symbol::base($request->base)
                ->quote($request->quote)
                ->pair($request->pair)
                ->buyIsActive($request->buy_is_active)
                ->sellIsActive($request->sell_is_active)
                ->search($request->search)
                ->tradeable()
                ->with('types')
                ->with('info')
                ->with('validation')
                ->get();

            return (new SymbolCollection($symbols))->setCustomWith([
                'message'   => Lang::get("trade::symbol.list")
            ]);

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();

            // 
        }
    }

    public function info($pair = 'BTCUSDT')
    {
        try {

            $symbol = Symbol::pair($pair)
                ->with('info')
                ->firstOrFail();

            return new SymbolInfoResource($symbol->info);

            // 
        } catch (Exception $e) {

            return app(ServerErrorException::class)->report($e)->response();
        }
    }

    public function symbolQuotes(Request $request)
    {
        $quotes = Symbol::select('quote')
            ->groupBy('quote')
            ->get();

        return Json::response(200, '', collect($quotes)->pluck('quote')->toArray());
    }
}
