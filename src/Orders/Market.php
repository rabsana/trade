<?php

namespace Rabsana\Trade\Orders;

use Exception;
use Rabsana\Trade\Contracts\Abstracts\Order;
use Rabsana\Trade\Exceptions\MarketDepthIsNotEnoughException;
use Rabsana\Trade\Helpers\Math;
use Rabsana\Trade\Models\Symbol;
use Rabsana\Trade\Models\SymbolOrder;
use Rabsana\Trade\Tasks\GetMakersOrderTask;
use Rabsana\Trade\Tasks\GetTakersOrderTask;

class Market extends Order
{
    public $symbol;
    public $baseQty;
    public $quoteQty;
    public $commissionInfo;
    public $commission;
    public $commissionPercent;


    public function store(Symbol $symbol)
    {
        try {

            $this->symbol = $symbol;

            request()->merge(['price' => $this->getPriceFromMarketDepth()]);

            $order = SymbolOrder::create($this->createStoreArray());

            return $order;

            // 
        } catch (MarketDepthIsNotEnoughException $e) {

            throw new MarketDepthIsNotEnoughException($e->getMessage());

            // 
        } catch (Exception $e) {

            throw new Exception($e);

            // 
        }
    }

    protected function getPriceFromMarketDepth(): float
    {
        $baseQty = request()->get('base_qty');
        $price = 0;

        if (request()->get('side') == 'BUY') {

            $sellPositions = app(GetMakersOrderTask::class)->run($this->symbol->pair, 500);

            foreach ($sellPositions as $item) {

                $notFilled = Math::subtract((float) $item->base_qty, (float)$item->filled_base_qty);

                $baseQty = Math::subtract((float) $baseQty, (float) $notFilled);

                if ($baseQty <= 0) {
                    $price = $item->price;
                    break;
                }
            }

            // 
        } elseif (request()->get('side') == 'SELL') {


            $buyPositions = app(GetTakersOrderTask::class)->run($this->symbol->pair, 500);

            foreach ($buyPositions as $item) {
                $notFilled = Math::subtract((float) $item->base_qty, (float)$item->filled_base_qty);

                $baseQty = Math::subtract((float) $baseQty, (float) $notFilled);

                if ($baseQty <= 0) {
                    $price = $item->price;
                    break;
                }
            }

            // 
        }

        if ($price == 0) {
            throw new MarketDepthIsNotEnoughException(" عمق بازار برای " . __("trade::symbolOrder." . request()->get('side')) . " " . $this->symbol->base . " کافی نمی باشد ");
        }

        return (float) $price;
    }

    protected function createStoreArray(): array
    {
        $this->setData();

        return [
            'symbol_order_status_id'    => SymbolOrder::MARKET,
            'symbol_order_type_id'      => SymbolOrder::CREATED,

            'orderable_type'            => (empty(request()->user())) ? NULL : get_class(request()->user()),
            'orderable_id'              => optional(request()->user())->id ?: NULL,

            'base'                      => $this->symbol->base,
            'quote'                     => $this->symbol->quote,
            'pair'                      => $this->symbol->pair,
            'base_name'                 => $this->symbol->base_name,
            'quote_name'                => $this->symbol->quote_name,
            'pair_name'                 => $this->symbol->pair_name,

            'side'                      => request()->get('side'),

            'original_base_qty'         => $this->baseQty['original'],
            'base_qty'                  => $this->baseQty['base'],
            'original_quote_qty'        => $this->quoteQty['original'],
            'quote_qty'                 => $this->quoteQty['quote'],

            'filled_base_qty'           => 0,
            'filled_quote_qty'          => 0,

            'price'                     => request()->get('price'),

            'commission'                => $this->commission,
            'commission_percent'        => $this->commissionPercent,

            'token'                     => NULL,

            'symbol_info'               => $this->symbol,
            'commission_info'           => $this->commissionInfo,

            'description'               => NULL,
            'user_description'          => request()->get('user_description', ''),

            'created_at'                => now()
        ];
    }

    protected function setData()
    {
        $this->commissionInfo = $this->getCommissionInfo();
        $this->commissionPercent = $this->getCommissionPercent($this->commissionInfo);

        $this->baseQty = $this->getBaseQtyInfo();
        $this->quoteQty = $this->getQuoteQtyInfo();
    }

    protected function getBaseQtyInfo()
    {
        $original = request()->get('base_qty');
        $base = $original;

        if (strtoupper(request()->get('side')) == 'BUY') {

            $this->commission = Math::divide((float) Math::multiply((float) $base, (float) $this->commissionPercent), 100);
            $base = Math::subtract((float) $base, $this->commission);

            // 
        }

        return [
            'original'  => $original,
            'base'      => $base,
        ];
    }

    protected function getQuoteQtyInfo()
    {
        $original = Math::multiply((float)$this->baseQty['original'], (float)request()->get('price'));
        $quote = $original;

        if (strtoupper(request()->get('side')) == 'SELL') {

            $this->commission = Math::divide((float) Math::multiply((float) $quote, (float) $this->commissionPercent), 100);
            $quote = Math::subtract((float) $quote, $this->commission);

            // 
        }

        return [
            'original'  => $original,
            'quote'     => $quote,
        ];
    }
}
