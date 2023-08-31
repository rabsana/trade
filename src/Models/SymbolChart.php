<?php

namespace Rabsana\Trade\Models;

use Illuminate\Database\Eloquent\Model;
use Rabsana\Trade\Helpers\Math;

class SymbolChart extends Model
{
    // Use traits


    // Config the model
    protected $guarded = ['id'];


    // Filters
    public function scopeSymbol($query, $symbol = NULL)
    {
        if (!empty($symbol)) {

            // get the symbol id
            $symbol = Symbol::pair($symbol)->firstOrFail();

            return $query->where('symbol_id', $symbol->id);

            // 
        }
        return $query;
    }

    public function scopeCandle($query, $candle = NULL)
    {
        if (!empty($candle)) {
            return $query->where('candle', $candle);
        }

        return $query;
    }

    public function scopeTodayCandles($query)
    {
        return $query->where('open_time', '>=', strtotime("today"))
            ->where('close_time', '<=', strtotime("tomorrow"));
    }

    // Relations

    public function symbol()
    {
        return $this->belongsTo(Symbol::class);
    }


    // Accessors

    public function getOpenDateAttribute()
    {
        return date('Y-m-d H:i:s', $this->open_time);
    }

    public function getOpenAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getHighAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getLowAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getCloseAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getCloseDateAttribute()
    {
        return date('Y-m-d H:i:s', $this->close_time);
    }

    public function getBaseVolumeAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getQuoteVolumeAttribute($value)
    {
        return Math::number((float)$value);
    }

    public function getTradeNumbersAttribute($value)
    {
        return (int) $value;
    }

    // Mutators


    // Extra methods
}
