<?php

namespace Rabsana\Trade\Http\Resources\Symbol;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Http\Resources\SymbolInfo\SymbolInfoResource;
use Rabsana\Trade\Http\Resources\SymbolType\SymbolTypeResource;
use Rabsana\Trade\Http\Resources\SymbolValidation\SymbolValidationResource;
use Rabsana\Trade\Traits\ResourceTrait;

class SymbolResource extends JsonResource
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
            'base'              => $this->base,
            'quote'             => $this->quote,
            'pair'              => $this->pair,
            'base_name'         => $this->base_name,
            'quote_name'        => $this->quote_name,
            'pair_name'         => $this->pair_name,
            'base_lower_case'   => $this->base_lower_case,
            'quote_lower_case'  => $this->quote_lower_case,
            'pair_lower_case'   => $this->pair_lower_case,
            'description'       => $this->description,
            'priority'          => $this->priority,
            'buy_is_active'     => $this->buy_is_active,
            'sell_is_active'    => $this->sell_is_active,
            'base_media'        => $this->base_media,
            'quote_media'       => $this->quote_media,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
            'info'              => new SymbolInfoResource($this->whenLoaded('info')),
            'types'             => SymbolTypeResource::collection($this->whenLoaded('types')),
            'validation'        => new SymbolValidationResource($this->whenLoaded('validation')),
        ];
    }
}
