<?php

namespace Rabsana\Trade\Models;

use Illuminate\Database\Eloquent\Model;
use Rabsana\Trade\Helpers\Math;

class SymbolOrderTrade extends Model
{
    // Use traits


    // Config the model
    protected $guarded = ['id'];


    // Filters


    // Relations

    public function taker()
    {
        return $this->taker_order();
    }

    public function maker()
    {
        return $this->maker_order();
    }


    // Accessors

    public function getTakerQtyAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getTakerQtyPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->taker_qty);
    }

    public function getMakerQtyAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getMakerQtyPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->maker_qty);
    }

    public function getTakerPriceAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getTakerPricePrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->taker_price);
    }


    public function getMakerPriceAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getMakerPricePrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->maker_price);
    }



    // Mutators

    public function setTakerQtyAttribute($taker_qty): void
    {
        $this->attributes['taker_qty'] = Math::number((float)$taker_qty);
    }

    public function setMakerQtyAttribute($maker_qty): void
    {
        $this->attributes['maker_qty'] = Math::number((float)$maker_qty);
    }

    public function setBaseCommissionAttribute($base_commission): void
    {
        $this->attributes['base_commission'] = Math::number((float)$base_commission);
    }

    public function setQuoteCommissionAttribute($quote_commission): void
    {
        $this->attributes['quote_commission'] = Math::number((float)$quote_commission);
    }

    public function setPriceAttribute($price): void
    {
        $this->attributes['price'] = Math::number((float)$price);
    }

    // Extra methods

    private function taker_order()
    {
        return $this->belongsTo(SymbolOrder::class);
    }

    private function maker_order()
    {
        return $this->belongsTo(SymbolOrder::class);
    }
}
