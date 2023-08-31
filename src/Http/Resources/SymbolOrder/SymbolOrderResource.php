<?php

namespace Rabsana\Trade\Http\Resources\SymbolOrder;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Http\Resources\SymbolOrderTrade\SymbolOrderTradeCollection;
use Rabsana\Trade\Http\Resources\SymbolStatus\SymbolStatusResource;
use Rabsana\Trade\Http\Resources\SymbolType\SymbolTypeResource;
use Rabsana\Trade\Traits\ResourceTrait;

class SymbolOrderResource extends JsonResource
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
            'status_id'                     => (int) $this->symbol_order_status_id,
            'type_id'                       => (int) $this->symbol_order_type_id,
//            'orderable'                     => $this->orderable,
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
            'commission'                    => $this->commission,
            'commission_prettified'         => $this->commission_prettified . ' ' . $this->commission_symbol,
            'commission_percent'            => $this->commission_percent,
            'commission_symbol'             => $this->commission_symbol,
            'received_money'                => $this->received_money . ' ' . $this->commission_symbol,
            'token'                         => $this->token,
            'description'                   => $this->description,
            'user_description'              => $this->user_description,
            'cancelable'                    => $this->cancelable,
            'created_at'                    => $this->created_at,
            'updated_at'                    => $this->updated_at,
            'filling_at'                    => $this->filling_at,
            'filled_at'                     => $this->filled_at,
            'canceled_at'                   => $this->canceled_at,
            'failed_at'                     => $this->failed_at,
            'jcreated_at'                   => $this->jcreated_at,
            'jupdated_at'                   => $this->jupdated_at,
            'jfilling_at'                   => $this->jfilling_at,
            'jfilled_at'                    => $this->jfilled_at,
            'jcanceled_at'                  => $this->jcanceled_at,
            'jfailed_at'                    => $this->jfailed_at,
            'base_media'                    => $this->base_media,
            'quote_media'                   => $this->quote_media,
            'equivalent_to_tomans'          => $this->equivalent_to_tomans,
            'equivalent_to_tomans_prettified'          => number_format($this->equivalent_to_tomans),
            'total_price'                   => $this->total_price,
            'total_price_prettified'        => number_format($this->total_price),
            'average_price'                 => $this->averagePrice(),
            'average_price_prettified'      => number_format($this->averagePrice()),
            'makers'                        => new SymbolOrderTradeCollection($this->whenLoaded('makers')),
            'takers'                        => new SymbolOrderTradeCollection($this->whenLoaded('takers')),
            'status'                        => new SymbolStatusResource($this->whenLoaded('status')),
            'type'                          => new SymbolTypeResource($this->whenLoaded('type')),
        ];
    }
}
