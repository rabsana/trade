<?php

namespace Rabsana\Trade\Trades;

use Exception;
use Rabsana\Trade\Helpers\Math;
use Rabsana\Trade\Jobs\OrderJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Tasks\GetMakersOrderTask;
use Rabsana\Trade\Tasks\GetTakersOrderTask;
use Rabsana\Trade\Contracts\Abstracts\Trade;
use Rabsana\Trade\Actions\OrderHaveFilledAction;
use Rabsana\Trade\Actions\GetUserCommissionAction;
use Rabsana\Trade\Exceptions\BuyOrSellNotFoundToMatched;


class FIFO extends Trade
{
    public $number;
    public $pair;

    public $symbols;
    public $buyOrders;
    public $sellOrders;

    public $updateOrderArray = [];
    public $insertTradeArray = [];

    public function match($number = 100, $pair = NULL)
    {
        DB::beginTransaction();

        try {

            $this->number = $number;
            $this->pair = $pair;

            // fetch the orders base on symbol and side
            $this->setSymbols();
            $this->setBuyOrders();
            $this->setSellOrders();

            // start to match the buy and sell orders
            $this->matchOrders();

            // update database
            $this->setUpdateOrderArray();

            $this->updateOrders();
            $this->insertTrades();
            $this->checkTrades();

            $filledOrders = collect($this->updateOrderArray)->where('symbol_order_status_id', SymbolOrder::FILLED)->all();
            $filledOrdersCount = collect($filledOrders)->count();
            if ($filledOrdersCount) {
                app(OrderHaveFilledAction::class)->run($filledOrders);
            }

            DB::commit();
            $this->message = 'Orders matched successfully';


            //
        } catch (BuyOrSellNotFoundToMatched $e) {

            $this->message = $e->getMessage();
            DB::rollBack();

            //
        } catch (Exception $e) {

            Log::debug("rabsana-trade-server-error :" . $e);
            $this->message = $e->getMessage();
            DB::rollBack();
        }

        return $this->response(true);
    }

    public function setSymbols(): FIFO
    {
        $this->symbols = SymbolOrder::select('pair')
            ->pair($this->pair)
            ->tradeable()
            ->groupBy('pair')
            ->get();

        return $this;
    }

    public function setBuyOrders(): FIFO
    {
        foreach ($this->symbols as $item) {

            $this->buyOrders = collect($this->buyOrders)->merge(
                app(GetTakersOrderTask::class)->run($item->pair, $this->number)
            );
        }

        // check if there are some buy orders to match
        if (empty($this->buyOrders)) {
            throw new BuyOrSellNotFoundToMatched("There are not any buy orders to matched");
        }

        return $this;
    }

    public function setSellOrders(): FIFO
    {
        foreach ($this->symbols as $item) {

            $this->sellOrders = collect($this->sellOrders)->merge(
                app(GetMakersOrderTask::class)->run($item->pair, $this->number)
            );
        }

        // check if there are some sell orders to match
        if (empty($this->sellOrders)) {
            throw new BuyOrSellNotFoundToMatched("There are not any sell orders to matched");
        }

        return $this;
    }

    protected function getCommission($type, $user)
    {
        $commissionInfo = app(GetUserCommissionAction::class)->run($user);
        $commissionPercent = $type == 'taker' ? $commissionInfo['takerFee'] : $commissionInfo['makerFee'];
        return $commissionPercent;
    }

    public function matchOrders()
    {
        // fill the buy orders with sell orders
        foreach ($this->buyOrders as $buyKey => $buyItem) {

            // the buy order has already filled. go to next order buy
            if ($buyItem->symbol_order_status_id == SymbolOrder::FILLED) {
                continue;
            }

            foreach ($this->sellOrders as $sellKey => $sellItem) {

                // the buy order has already filled in sell orders loop. break the sell loops for this order buy
                if ($buyItem->symbol_order_status_id == SymbolOrder::FILLED) {
                    break;
                }

                if (
                    $buyItem->pair_lower_case != $sellItem->pair_lower_case || // the symbols does not match
                    Math::equal((float) $sellItem->filled_base_qty, (float) $sellItem->base_qty) || // the sell order has already filled
                    Math::lessThan((float) $buyItem->price, (float) $sellItem->price) // buy order's price must greaterThanOrEqual the sell order's price. FIFO condition
                ) {
                    continue;
                }

                // arg1 at add method: the amount of buy order that has already filled
                // arg2 at add method: the amount of sell order that has not filled yet
                $amount = Math::add((float) $buyItem->filled_base_qty, (float) Math::subtract((float) $sellItem->base_qty, (float) $sellItem->filled_base_qty));

                if (Math::lessThanOrEqual((float) $amount, (float)$buyItem->base_qty)) {

                    // not filled sell order <= not filled buy order: the buy order amount is greaterThanOrEqual than the sell order amount
                    // to get buy order filled: use all amount of sell order

                    // making trade array
                    $remainSellerBaseQty = Math::subtract((float) $sellItem->base_qty, (float) $sellItem->filled_base_qty);

                   

                    // determine taker and maker
                    $type = $buyItem->created_at < $sellItem->created_at ? 'buy' : 'sell';
                    // determine taker_price and maker_price
                    $maker = $type == 'buy' ? $buyItem : $sellItem;
                    $taker = $type == 'buy' ? $sellItem : $buyItem;
                    $maker_price = $maker->price;
                    $taker_price = $taker->price;

                    //determines the commission
                    $taker_commission = $this->getCommission('taker', $taker->user);
                    $maker_commission = $this->getCommission('maker', $maker->user);

                    $base_commission = $this->getBaseQtyAfterCommission($remainSellerBaseQty, $type == 'buy' ? $maker_commission : $taker_commission);
                    $quote_commission = $this->getQuoteQtyAfterCommission($remainSellerBaseQty, $type == 'sell' ? $maker_commission : $taker_commission, $maker_price);
                    
                    // ATENTION: taker_qty means base_qty and maker_qty means quote_qty
                    $this->insertTradeArray[] = [
                        'taker_order_id'    => $type == 'sell' ? $buyItem->id : $sellItem->id,
                        'maker_order_id'    => $type == 'buy' ? $buyItem->id : $sellItem->id,
                        'taker_qty'         => $remainSellerBaseQty, // remain of base qty of sell order that should give to buy order 
                        'maker_qty'         => Math::multiply((float)$remainSellerBaseQty, (float) $maker_price), // original quote qty that should give to sell order,
                        'base_commission'    => $base_commission,
                        'quote_commission'   => $quote_commission,
                        // 'base_qty'          => $remainSellerBaseQty, // remain of base qty of sell order that should give to buy order 
                        // 'quote_qty'             => Math::multiply((float)$remainSellerBaseQty, (float) $taker_price), // quote qty that should give to sell order,
                        'taker_price'       => $taker_price,
                        'maker_price'       => $maker_price,
                        'equivalent_to_tomans' => $maker_price  * $this->getBasePrice(strtolower($sellItem->quote) , 'sell') * $remainSellerBaseQty,
                        'created_at'        => now(),
                        'type'              => $type
                    ];

                    // sell order get filled here
                    $sellItem->equivalent_to_tomans += $maker_price * $remainSellerBaseQty * $this->getBasePrice(strtolower($sellItem->quote), 'sell');
                    $sellItem->total_price += $maker_price * $remainSellerBaseQty;
                    $sellItem->commission = $buyItem->commission + $quote_commission;
                    $sellItem->symbol_order_status_id = SymbolOrder::FILLED;
                    $sellItem->filled_base_qty = $sellItem->base_qty;
                    $sellItem->filled_quote_qty = $sellItem->quote_qty;
                    $sellItem->modified = 1;


                    // buy order get filling
                    $buyItem->equivalent_to_tomans += $maker_price * $remainSellerBaseQty * $this->getBasePrice(strtolower($buyItem->quote), 'buy');
                    $buyItem->total_price += $maker_price * $remainSellerBaseQty;
                    $buyItem->commission = $buyItem->commission + $base_commission;
                    $buyItem->symbol_order_status_id = (Math::equal((float) $buyItem->base_qty, (float) $amount)) ? SymbolOrder::FILLED : SymbolOrder::FILLING;
                    $buyItem->filled_base_qty = $amount;
                    $buyItem->filled_quote_qty = Math::multiply((float)$buyItem->filled_base_qty, (float)$buyItem->price);
                    $buyItem->modified = 1;

                    //
                } else {
                    // not filled sell order > not filled buy order: the buy order amount is less than the sell order amount
                    // to get sell order filled: use all amount of buy order

                    // making trade array
                    $remainBuyBaseQty = Math::subtract((float) $buyItem->base_qty, (float) $buyItem->filled_base_qty);

                    $type = $buyItem->created_at < $sellItem->created_at ? 'buy' : 'sell';
                    
                    $maker = $type == 'buy' ? $buyItem : $sellItem;
                    $taker = $type == 'buy' ? $sellItem : $buyItem;
                    $maker_price = $maker->price;
                    $taker_price = $taker->price;


                    //determines the commission
                    $taker_commission = $this->getCommission('taker', $taker->user);
                    $maker_commission = $this->getCommission('maker', $maker->user);

                    $base_commission = $this->getBaseQtyAfterCommission($remainBuyBaseQty, $type == 'buy' ? $maker_commission : $taker_commission);
                    $quote_commission = $this->getQuoteQtyAfterCommission($remainBuyBaseQty, $type == 'sell' ? $maker_commission : $taker_commission, $maker_price);
                    
                    // ATENTION: taker_qty means base_qty and maker_qty means quote_qty
                    $this->insertTradeArray[] = [
                        'taker_order_id'    => $type == 'sell' ? $buyItem->id : $sellItem->id,
                        'maker_order_id'    => $type == 'buy' ? $buyItem->id : $sellItem->id,
                        'taker_qty'         => $remainBuyBaseQty,
                        'maker_qty'         => Math::multiply((float)$remainBuyBaseQty, (float) $maker_price),
                        'base_commission'    => $base_commission,
                        'quote_commission'   => $quote_commission,
                        'taker_price'       => $taker_price,
                        'maker_price'       => $maker_price,
                        'equivalent_to_tomans' => $maker_price * $this->getBasePrice(strtolower($sellItem->quote) , 'sell') * $remainBuyBaseQty,
                        'created_at'        => now(),
                        'type'              => $type
                    ];

                    // sell order get filling
                    $sellItem->equivalent_to_tomans += $maker_price * $remainBuyBaseQty * $this->getBasePrice(strtolower($sellItem->quote), 'sell');
                    $sellItem->total_price += $maker_price * $remainBuyBaseQty;
                    $sellItem->commission = $sellItem->commission + $quote_commission;
                    $sellItem->symbol_order_status_id = SymbolOrder::FILLING;
                    $sellItem->filled_base_qty = Math::add((float) $sellItem->filled_base_qty, (float) Math::subtract((float) $buyItem->base_qty, (float)$buyItem->filled_base_qty));
                    $sellItem->filled_quote_qty = Math::multiply((float) $sellItem->filled_base_qty, (float)$sellItem->price);
                    $sellItem->modified = 1;

                    // buy order get filled here
                    $buyItem->equivalent_to_tomans += $maker_price * $remainBuyBaseQty * $this->getBasePrice(strtolower($buyItem->quote), 'buy');
                    $buyItem->total_price += $maker_price * $remainBuyBaseQty;
                    $buyItem->commission = $buyItem->commission + $base_commission;
                    $buyItem->symbol_order_status_id = SymbolOrder::FILLED;
                    $buyItem->filled_base_qty = $buyItem->base_qty;
                    $buyItem->filled_quote_qty = $buyItem->quote_qty;
                    $buyItem->modified = 1;

                    //
                }

                //
            }
        }
    }


    protected function getBaseQtyAfterCommission($qty, $commissionPercent)
    {
        $commission = Math::divide((float) Math::multiply((float) $qty, (float) $commissionPercent), 100);

        return $commission;
    }

    protected function getQuoteQtyAfterCommission($qty, $commissionPercent, $price)
    {
        $qty = Math::multiply((float)$qty, (float)$price);
        $commission = Math::divide((float) Math::multiply((float) $qty, (float) $commissionPercent), 100);

        return $commission;
    }
    

    public function setUpdateOrderArray()
    {
        $this->createUpdateOrderArray($this->buyOrders);
        $this->createUpdateOrderArray($this->sellOrders);
    }

    public function createUpdateOrderArray($orders)
    {
        foreach ($orders as $order) {

            if (empty($order->modified)) {
                continue;
            }

            $this->updateOrderArray[] = [
                'id'                        => $order->id,
                // 'pair'                      => $order->pair,
                // 'price'                     => $order->price,
                'symbol_order_status_id'    => $order->symbol_order_status_id,
                'filled_base_qty'           => Math::number((float)$order->filled_base_qty),
                'filled_quote_qty'          => Math::number((float)$order->filled_quote_qty),
                'updated_at'                => now(),
                'filling_at'                => ($order->symbol_order_status_id == SymbolOrder::FILLING) ? now() : NULL,
                'filled_at'                 => ($order->symbol_order_status_id == SymbolOrder::FILLED) ? now() : NULL,
                'equivalent_to_tomans'      => $order->equivalent_to_tomans,
                'total_price'               => $order->total_price,
                'commission'                => $order->commission
            ];
            OrderJob::dispatch($order->id);
        }
    }

    private function getBasePrice($quote, $side)
    {
        if (strtoupper($quote) !== 'TOMAN'){

            return \App\Currency::whereAbbreviation($quote)->first()->{$side};
        }
        return 1;
    }
}
