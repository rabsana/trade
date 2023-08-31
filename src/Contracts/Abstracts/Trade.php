<?php

namespace Rabsana\Trade\Contracts\Abstracts;

use Exception;
use Rabsana\Trade\Contracts\Interfaces\Trade as InterfacesTrade;
use Mavinoo\Batch\Batch;
use Rabsana\Trade\Models\SymbolOrder;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Cache;
use Rabsana\Trade\Jobs\TradeJob;
use Rabsana\Trade\Models\SymbolOrderTrade;

abstract class Trade implements InterfacesTrade
{
    public $message = '';
    public $startAt;
    public $endAt;
    public $matching;
    public $tradesIds = [];

    public function __construct()
    {
        $this->startAt = time();

        if (Cache::has('rabsana-trade-is-matching-the-orders')) {
            $this->message = "Trade system is matching the orders now. please try again in a few seconds";
            throw new Exception($this->message);
        }

        Cache::put('rabsana-trade-is-matching-the-orders', true, 60);
    }

    public function UpdateOrders($index = 'id')
    {
        if (!empty($this->updateOrderArray)) {
            foreach (array_chunk($this->updateOrderArray, 100) as $item) {
                (new Batch(app()->make(DatabaseManager::class)))->update(new SymbolOrder(), $item, $index);
            }
        }
        return $this;
    }

    public function insertTrades()
    {
        if (!empty($this->insertTradeArray)) {
            foreach (array_chunk($this->insertTradeArray, 100) as $item) {
                SymbolOrderTrade::insert($item);
                
                $tradesIds = SymbolOrderTrade::orderBy('id','desc')->take(count($item))->pluck('id');
                // add trades ids to tradesIds array
                $this->tradesIds = array_merge($this->tradesIds, $tradesIds->toArray());
            }
        }
        return $this;
    }

    public function checkTrades()
    {
        if (!empty($this->tradesIds)) {
            //sort trades ids            
            sort($this->tradesIds);
            foreach ($this->tradesIds as $trade) {
                TradeJob::dispatch($trade);
            }
        }
        return $this;
    }

    public function response($matchingHasDone = false)
    {
        $this->endAt = time();

        if ($matchingHasDone) {
            Cache::forget('rabsana-trade-is-matching-the-orders');
        }

        $filledOrders = collect($this->updateOrderArray)->where('symbol_order_status_id', SymbolOrder::FILLED)->all();
        $filledOrdersCount = collect($filledOrders)->count();

        return [
            'message'       => $this->message,
            'startAt'       => $this->startAt,
            'endAt'         => $this->endAt,
            'duration'      => $this->endAt - $this->startAt,
            'ordersUpdated' => collect($this->updateOrderArray)->count(),
            'ordersFilling' => collect($this->updateOrderArray)->where('symbol_order_status_id', SymbolOrder::FILLING)->count(),
            'ordersFilled'  => $filledOrdersCount,
            'tradeInserted' => collect($this->insertTradeArray)->count()
        ];
    }
}
