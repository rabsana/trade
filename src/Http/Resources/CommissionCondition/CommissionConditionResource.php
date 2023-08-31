<?php

namespace Rabsana\Trade\Http\Resources\CommissionCondition;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Traits\ResourceTrait;

class CommissionConditionResource extends JsonResource
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
            'id'                    => $this->id,
            'operand'               => $this->operand,
            'operator'              => $this->operator,
            'property'              => $this->property,
            'period'                => $this->period,
        ];
    }
}
