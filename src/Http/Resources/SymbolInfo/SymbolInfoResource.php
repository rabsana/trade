<?php

namespace Rabsana\Trade\Http\Resources\SymbolInfo;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Traits\ResourceTrait;

class SymbolInfoResource extends JsonResource
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
            'price'                                 => $this->price,
            'price_prettified'                      => $this->price_prettified,

            'last_day_high'                         => $this->last_day_high,
            'last_day_high_prettified'              => $this->last_day_high_prettified,
            'last_day_low'                          => $this->last_day_low,
            'last_day_low_prettified'               => $this->last_day_low_prettified,
            'last_day_base_volume'                  => $this->last_day_base_volume,
            'last_day_base_volume_prettified'       => $this->last_day_base_volume_prettified,

            'today_high'                            => $this->today_high,
            'today_high_prettified'                 => $this->today_high_prettified,
            'today_low'                             => $this->today_low,
            'today_low_prettified'                  => $this->today_low_prettified,
            'today_base_volume'                     => $this->today_base_volume,
            'today_base_volume_prettified'          => $this->today_base_volume_prettified,

            'change_percent'                        => trimTrailingZeroes(number_format($this->change_percent ?? 0, 2)),
        ];
    }
}
