<?php

namespace Rabsana\Trade\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;

class SymbolOrderStatus extends Model
{
    // Use traits


    // Config the model
    protected $guarded = ['id'];

    protected $appends =
    [
        'name_lower_case',
        'name_translated',
    ];


    // Filters

    public function scopeName($query, $name = NULL)
    {
        if (!empty($name)) {
            return $query->whereRaw('UPPER(name) = ?', [strtoupper($name)]);
        }
        return $query;
    }


    // Relations

    public function orders()
    {
        return $this->symbol_orders();
    }


    // Accessors

    public function getNameLowerCaseAttribute(): string
    {
        return strtolower($this->name);
    }

    public function getNameAttribute($value): string
    {
        return strtoupper($value);
    }

    public function getNameTranslatedAttribute(): string
    {
        return Lang::get("trade::symbolOrderStatus.$this->name");
    }

    // Mutators


    // Extra methods

    private function symbol_orders()
    {
        return $this->hasMany(SymbolOrder::class);
    }
}
