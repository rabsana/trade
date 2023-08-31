<?php

namespace Rabsana\Trade\Http\Resources\SymbolOrderTrade;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Traits\ResourceTrait;

class SymbolOrderTradeResource extends JsonResource
{
    use ResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                        => $this->id,
            'taker_order_id'            => $this->taker_order_id,
            'maker_order_id'            => $this->maker_order_id,
            'taker_qty'                 => $this->taker_qty,
            'taker_qty_prettified'      => $this->taker_qty_prettified,
            'maker_qty'                 => $this->maker_qty,
            'maker_qty_prettified'      => $this->maker_qty_prettified,
            'taker_price'               => $this->taker_price,
            'taker_price_prettified'    => $this->taker_price_prettified,
            'maker_price'               => $this->maker_price,
            'maker_price_prettified'    => $this->maker_price_prettified,
        ];
    }
}
