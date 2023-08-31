<?php

namespace Rabsana\Trade\Http\Resources\Market;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Traits\ResourceTrait;

class LatestFilledOrdersResource extends JsonResource
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
            'base'                          => $this->base,
            'quote'                         => $this->quote,
            'pair'                          => $this->pair,
            'base_name'                     => $this->base_name,
            'quote_name'                    => $this->quote_name,
            'pair_name'                     => $this->pair_name,
            'base_lower_case'               => $this->base_lower_case,
            'quote_lower_case'              => $this->quote_lower_case,
            'pair_lower_case'               => $this->pair_lower_case,
            'side'                          => $this->side,
            'side_lower_case'               => $this->side_lower_case,
            'side_translated'               => $this->side_translated,
            'original_base_qty'             => $this->original_base_qty,
            'original_base_qty_prettified'  => $this->original_base_qty_prettified,
            'base_qty'                      => $this->base_qty,
            'base_qty_prettified'           => $this->base_qty_prettified,
            'original_quote_qty'            => $this->original_quote_qty,
            'original_quote_qty_prettified' => $this->original_quote_qty_prettified,
            'quote_qty'                     => $this->quote_qty,
            'quote_qty_prettified'          => $this->quote_qty_prettified,
            'filled_base_qty'               => $this->filled_base_qty,
            'filled_base_qty_prettified'    => $this->filled_base_qty_prettified,
            'filled_quote_qty'              => $this->filled_quote_qty,
            'filled_quote_qty_prettified'   => $this->filled_quote_qty_prettified,
            'filled_percent'                => $this->filled_percent,
            'price'                         => $this->price,
            'price_prettified'              => $this->price_prettified,
            'date'                          => $this->filled_at,
            'jdate'                         => $this->jfilled_at,
        ];
    }
}
