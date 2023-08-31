<?php

namespace Rabsana\Trade\Http\Controllers\Api;


use Illuminate\Http\Request;
use Rabsana\Trade\Helpers\Json;
use Rabsana\Trade\Helpers\Math;
use Illuminate\Routing\Controller;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Classes\CustomPaginator;
use Rabsana\Trade\Models\SymbolOrderTrade;
use Rabsana\Trade\Exceptions\ServerErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Rabsana\Trade\Exceptions\ModelNotFoundErrorException;
use Rabsana\Trade\Http\Resources\Trades\TradesCollection;

class ApiTradeController extends Controller
{
    public function index(Request $request)
    {
        $type = null;
        $id = null;
        if($request->get('mode', 'user') == 'user') {
            $type = !empty($request->user()) ? get_class($request->user()) : NULL;
            $id = optional($request->user())->id;
        }
        $orders = 
        collect(
            SymbolOrder::orderableFilter($type, $id)
            ->search($request->search)
            ->pair($request->pair)
            ->get()
        );

        $trades = [];
        collect($orders)->map(function ($order) use (&$trades, $id) {
            collect($order->takers)->map($this->getTrades($order, $trades, $id));
        });

        collect($orders)->map(function ($order) use (&$trades, $id) {
            collect($order->makers)->map($this->getTrades($order, $trades, $id));
        });
        
        if($request->get('mode', 'user') == 'user') {
            $trades = (new CustomPaginator())->paginate(collect($trades)->unique('id')->sortByDesc('created_at'),
                $request->get('per_page' , 20), $request->get('page', 1), [
                'path'     => route('rabsana-trade.api.v1.trades.index'),
                'pageName' => 'page'
            ]);
        } else {
            $trades = collect($trades)->unique('id')->sortByDesc('created_at')->take(20)->all();
        }
        

        return (new TradesCollection($trades));

    }

    public function last(Request $request)
    {

        try {
            $trade = SymbolOrderTrade::where(function($query) use($request) {
                $query->whereHas('taker',function ($query) use ($request){
                    $query->where('pair', $request->symbol);
                })->orWhereHas('maker',function ($query) use ($request){
                    $query->where('pair', $request->symbol); 
                });
            })->latest()->firstOrFail();

            return Json::response(200, '', [
                'base'  => $trade->maker->base,
                'type'  => $trade->type,
                'price' => $trade->maker_price
            ]);

        }catch (ModelNotFoundException $exception){

            return app(ModelNotFoundErrorException::class)->report($exception)->response();
        }
        catch (\Exception $exception){

            return app(ServerErrorException::class)->report($exception)->response();
        }

    }

    /**
     * @param $order
     * @param array $trades
     * @return \Closure
     */
    private function getTrades($order, array &$trades, $id = null): \Closure
    {
        return function ($item, $key) use ($order, &$trades, $id) {
            $item->id = $item->id;
            $item->pair = $order->pair_name;
            $item->side = $order->side_translated;
            $item->price = $item->maker_price;
            $item->commission = $order->commission_percent * $item->taker_qty * $item->maker_price;
            $item->kind = $item->type == 'sell' ? 'maker' : 'taker';
            $item->type = $item->type;
            $item->received_money = 0;
            $item->total_price = 0;
            if($id) {
                $commission =  $order->side == 'BUY' ? $item->base_commission : $item->quote_commission;
                $commssion_symbol = $order->side == 'BUY' ? $order->base : $order->quote;
                $total_price = Math::multiply((float) $item->taker_qty, (float) $item->maker_price);
                $item->commission = Math::number((float)$commission) . ' ' . $commssion_symbol;
                $item->received_money =  Math::number(Math::subtract($order->side == 'BUY' ? $item->taker_qty : $item->maker_qty, $commission)) . ' ' . $commssion_symbol;
                $item->total_price = $total_price;
            }

            $trades[] = $item;
        };
    }
}
