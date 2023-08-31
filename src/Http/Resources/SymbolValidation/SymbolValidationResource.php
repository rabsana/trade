<?php

namespace Rabsana\Trade\Http\Resources\SymbolValidation;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Traits\ResourceTrait;

class SymbolValidationResource extends JsonResource
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
            'id'                            => $this->id,
            'min_qty'                       => $this->min_qty,
            'max_qty'                       => $this->max_qty,
            'scale_qty'                     => $this->scale_qty,
            'min_price'                     => $this->min_price,
            'max_price'                     => $this->max_price,
            'scale_price'                   => $this->scale_price,
            'min_notional'                  => $this->min_notional,
            'max_notional'                  => $this->max_notional,
            'scale_notional'                => $this->scale_notional,
            'percent_order_price_up'        => $this->percent_order_price_up,
            'percent_order_price_down'      => $this->percent_order_price_down,
            'percent_order_price_minute'    => $this->percent_order_price_minute,
        ];
    }
}
