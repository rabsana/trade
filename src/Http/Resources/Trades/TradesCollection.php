<?php

namespace Rabsana\Trade\Http\Resources\Trades;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Rabsana\Trade\Traits\ResourceTrait;

class TradesCollection extends ResourceCollection
{
    use ResourceTrait;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
