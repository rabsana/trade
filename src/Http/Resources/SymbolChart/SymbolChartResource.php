<?php

namespace Rabsana\Trade\Http\Resources\SymbolChart;

use Illuminate\Http\Resources\Json\JsonResource;
use Rabsana\Trade\Traits\ResourceTrait;

class SymbolChartResource extends JsonResource
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
            'candle'            => $this->candle,
            'open_time'         => $this->open_time,
            'open_date'         => $this->open_date,
            'open'              => $this->open,
            'high'              => $this->high,
            'low'               => $this->low,
            'close'             => $this->close,
            'close_time'        => $this->close_time,
            'close_date'        => $this->close_date,
            'base_volume'       => $this->base_volume,
            'quote_volume'      => $this->quote_volume,
            'trade_numbers'     => $this->trade_numbers,
        ];
    }
}
