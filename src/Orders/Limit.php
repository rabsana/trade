<?php

namespace Rabsana\Trade\Orders;

use Exception;
use Rabsana\Trade\Contracts\Abstracts\Order;
use Rabsana\Trade\Helpers\Math;
use Rabsana\Trade\Models\Symbol;
use Rabsana\Trade\Models\SymbolOrder;

class Limit extends Order
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

            $order = SymbolOrder::create($this->createStoreArray());

            return $order;

            // 
        } catch (Exception $e) {

            throw new Exception($e);
        }
    }

    protected function createStoreArray(): array
    {
        $this->setData();

        return [
            'symbol_order_status_id'    => SymbolOrder::LIMIT,
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

            'price'                     => request()->get('price', 0),

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
        $this->commissionInfo = null;
        $this->commissionPercent = 0;

        $this->baseQty = $this->getBaseQtyInfo();
        $this->quoteQty = $this->getQuoteQtyInfo();
    }

    protected function getBaseQtyInfo()
    {
        $original = request()->get('base_qty');
        $base = $original;

        return [
            'original'  => $original,
            'base'      => $base,
        ];
    }

    protected function getQuoteQtyInfo()
    {
        $original = Math::multiply((float)$this->baseQty['original'], (float)request()->get('price'));
        $quote = $original;
        info($quote);
        return [
            'original'  => $original,
            'quote'     => $quote,
        ];
    }
}
