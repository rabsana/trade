<?php

namespace Rabsana\Trade\Http\Resources\SymbolStatus;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Traits\ResourceTrait;

class SymbolStatusResource extends JsonResource
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
            'name_lower_case'   => $this->name_lower_case,
            'name_translated'   => $this->name_translated,
        ];
    }
}
