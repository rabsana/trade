<?php

namespace Rabsana\Trade\Http\Resources\Commission;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Http\Resources\CommissionCondition\CommissionConditionCollection;
use Rabsana\Trade\Traits\ResourceTrait;

class CommissionResource extends JsonResource
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
            'id'                => $this->id,
            'name'              => $this->name,
            'taker_fee'         => $this->taker_fee,
            'maker_fee'         => $this->maker_fee,
            'symbol_quote'      => $this->symbol_quote,
            'conditions'        => new CommissionConditionCollection($this->whenLoaded('conditions')),
        ];
    }
}
