<?php

namespace Rabsana\Trade\Http\Resources\SymbolOrder;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Rabsana\Trade\Traits\ResourceTrait;

class SymbolOrderCollection extends ResourceCollection
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
        return $this->collection;
    }
}
