<?php

namespace Rabsana\Trade\Http\Resources\Trades;

use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;
use Rabsana\Trade\Traits\ResourceTrait;
use Rabsana\Core\Support\Facades\Math;

class TradesResource extends JsonResource
{
    use ResourceTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'   => $this->id,
            'qty'   => $this->taker_qty,
            'pair' => $this->pair,
            'side' => $this->side,
            'price' => $this->price,
            'equivalent_to_tomans' => $this->equivalent_to_tomans,
            'equivalent_to_tomans_prettified' => number_format($this->equivalent_to_tomans),
            'commission' => $this->commission,
            'kind' => $this->kind,
            'type' => $this->type,
            'total_price' => $this->total_price,
            'received_money' => $this->received_money,
            'created_at' => Jalalian::forge($this->created_at)->format('Y-m-d H:i:s')
        ];
    }
}
