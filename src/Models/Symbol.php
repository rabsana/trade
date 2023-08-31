<?php

namespace Rabsana\Trade\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Models\Scopes\SymbolPrioritySortingGlobalScope;

class Symbol extends Model
{
    // Use traits

    protected static function booted()
    {
        static::addGlobalScope(new SymbolPrioritySortingGlobalScope);
    }


    // Config the model
    protected $guarded = ['id'];

    protected $appends =
    [
        'base_lower_case',
        'quote_lower_case',
        'pair_lower_case',
        'base_media',
        'quote_media'
    ];


    // Filters

    public function scopeBase($query, $base = NULL)
    {
        if (!empty($base)) {
            return $query->whereRaw('UPPER(base) = ?', [strtoupper($base)]);
        }

        return $query;
    }

    public function scopeQuote($query, $quote = NULL)
    {
        if (!empty($quote)) {
            return $query->whereRaw('UPPER(quote) = ?', [strtoupper($quote)]);
        }

        return $query;
    }

    public function scopePair($query, $pair = NULL)
    {
        if (!empty($pair)) {
            return $query->whereRaw('UPPER(pair) = ?', [strtoupper($pair)]);
        }

        return $query;
    }

    public function scopeBuyIsActive($query, $buyIsActive = NULL)
    {
        if (is_numeric($buyIsActive) && in_array($buyIsActive, [0, 1])) {
            return $query->where('buy_is_active', $buyIsActive);
        }

        return $query;
    }

    public function scopeSellIsActive($query, $sellIsActive = NULL)
    {
        if (is_numeric($sellIsActive) && in_array($sellIsActive, [0, 1])) {
            return $query->where('sell_is_active', $sellIsActive);
        }

        return $query;
    }

    public function scopeSearch($query, $search = NULL)
    {
        if (!empty($search)) {
            return $query->where(function ($q) use ($search) {

                $q->orWhere('base', 'LIKE', "%$search%")
                    ->orWhere('quote', 'LIKE', "%$search%")
                    ->orWhere('pair', 'LIKE', "%$search%")
                    ->orWhere('base_name', 'LIKE', "%$search%")
                    ->orWhere('quote_name', 'LIKE', "%$search%")
                    ->orWhere('pair_name', 'LIKE', "%$search%");
            });
        }

        return $query;
    }

    public function scopeTradeable($query)
    {
        return $query->where(function ($q) {
            $q->orWhere('buy_is_active', 1)
                ->orWhere('sell_is_active', 1);
        });
    }


    // Relations

    public function types()
    {
        // wrap laravel naming convention
        return $this->symbol_order_types();
    }

    public function validation()
    {
        // wrap laravel naming convention
        return $this->symbol_validation();
    }

    public function info()
    {
        // wrap laravel naming convention
        return $this->symbol_info();
    }

    public function charts()
    {
        // wrap laravel naming convention
        return $this->symbol_charts();
    }


    // Accessors

    public function getBaseLowerCaseAttribute(): string
    {
        return strtolower($this->base);
    }

    public function getQuoteLowerCaseAttribute(): string
    {
        return strtolower($this->quote);
    }

    public function getPairLowerCaseAttribute(): string
    {
        return strtolower($this->pair);
    }

    public function getBaseAttribute($value): string
    {
        return strtoupper($value);
    }

    public function getQuoteAttribute($value): string
    {
        return strtoupper($value);
    }

    public function getPairAttribute($value): string
    {
        return strtoupper($value);
    }

    public function getBaseMediaAttribute(): array
    {
        return $this->getMedia($this->base);
    }

    public function getQuoteMediaAttribute(): array
    {
        return $this->getMedia($this->quote);
    }

    public function getBuyIsActiveAttribute($value): int
    {
        return (int) $value;
    }

    public function getSellIsActiveAttribute($value): int
    {
        return (int) $value;
    }

    public function getDescriptionAttribute($value): string
    {
        return (string) $value;
    }

    public function getCreatedAtAttribute($value): string
    {
        return $value ?: '';
    }

    public function getUpdatedAtAttribute($value): string
    {
        return $value ?: '';
    }

    public function getPriorityAttribute($value): int
    {
        return (int) $value;
    }

    // Mutators

    public function setBaseAttribute($base): void
    {
        $this->attributes['base'] = strtoupper($base);
    }

    public function setQuoteAttribute($quote): void
    {
        $this->attributes['quote'] = strtoupper($quote);
    }

    public function setPairAttribute($pair): void
    {
        $this->attributes['pair'] = strtoupper($pair);
    }

    public function setDescriptionAttribute($description): void
    {
        $this->attributes['description'] = (string) (strtolower($description) == 'null') ? '' : $description;
    }

    public function setPriorityAttribute($priority): void
    {
        $this->attributes['priority'] = (int) $priority;
    }

    public function setBuyIsActiveAttribute($buy_is_active): void
    {
        $this->attributes['buy_is_active'] = (int) ((bool)$buy_is_active);
    }

    public function setSellIsActiveAttribute($sell_is_active): void
    {
        $this->attributes['sell_is_active'] = (int) ((bool)$sell_is_active);
    }

    public function getBuyIsActivePrettifiedAttribute()
    {
        $active = Lang::get("trade::rabsana.active");
        $inactive = Lang::get("trade::rabsana.inactive");
        return $this->buy_is_active ? "<span class='text-success'>{$active}</span>" : "<span class='text-danger'>{$inactive}</span>";
    }

    public function getSellIsActivePrettifiedAttribute()
    {
        $active = Lang::get("trade::rabsana.active");
        $inactive = Lang::get("trade::rabsana.inactive");
        return $this->sell_is_active ? "<span class='text-success'>{$active}</span>" : "<span class='text-danger'>{$inactive}</span>";
    }

    // Extra methods

    private function symbol_order_types()
    {
        return $this->belongsToMany(SymbolOrderType::class)->withTimestamps();
    }

    private function symbol_validation()
    {
        return $this->hasOne(SymbolValidation::class)
            ->withDefault(function ($symbol_validation, $symbol) {
                $symbol_validation->id = 0;
                $symbol_validation->min_qty = '0.00000001';
                $symbol_validation->max_qty = '99999999';
                $symbol_validation->scale_qty = '8';
                $symbol_validation->min_price = '0.00000001';
                $symbol_validation->max_price = '99999999';
                $symbol_validation->scale_price = '8';
                $symbol_validation->min_notional = '10';
                $symbol_validation->max_notional = '99999999';
                $symbol_validation->scale_notional = '2';
                $symbol_validation->percent_order_price_up = '5';
                $symbol_validation->percent_order_price_down = '0.2';
                $symbol_validation->percent_order_price_minute = '5';
            });
    }

    private function symbol_info()
    {
        return $this->hasOne(SymbolInfo::class)
            ->withDefault(function ($symbol_info, $symbol) {
                $symbol_info->id = 0;
                $symbol_info->price = 0;
                $symbol_info->last_day_high = 0;
                $symbol_info->last_day_low = 0;
                $symbol_info->last_day_base_volume = 0;
                $symbol_info->today_high = 0;
                $symbol_info->today_low = 0;
                $symbol_info->today_base_volume = 0;
                $symbol_info->change_percent = 0;
            });
    }

    private function symbol_charts()
    {
        return $this->hasMany(SymbolChart::class);
    }

    private function getMedia(string $abbreviation): array
    {
        $media['image'] = $this->getImage($abbreviation);

        return $media;
    }

    private function getImage(string $abbreviation = 'not-found'): array
    {
        $image = [];
        $abbreviation = strtolower($abbreviation);

        foreach ($this->getImageSizes() as $size) {

            foreach ($this->getImageTypes() as $type) {

                $path = "image/symbols/{$size}/{$type}/{$abbreviation}.png";
                $storageInfo = $this->getStoragePath($path);
                $publicInfo = $this->getPublicPath($path);

                if (file_exists($storageInfo['publicPath'])) {
                    // first look for image at storage directory: someone uploaded an image
                    $imagePath = $storageInfo['assetPath'];

                    // 
                } elseif (file_exists($publicInfo['publicPath'])) {
                    // second look for image at assets directory
                    $imagePath = $publicInfo['assetPath'];

                    // 
                } elseif ($abbreviation != 'not-found') {
                    // return the not-found image
                    return $this->getImage();

                    // 
                } else {
                    // return nothing when "not-found.png" image does not exist
                    $imagePath = '';

                    // 
                }


                $image[$size . "px"][$type] = $imagePath;

                // 
            }
        }

        return $image;
    }

    private function getImageSizes(): array
    {
        return [32, 64, 128];
    }

    private function getImageTypes(): array
    {
        return ['black', 'color', 'white'];
    }

    private function getStoragePath(string $path): array
    {
        $publicPath = $this->getPrettifiedPath(public_path((config('PACKAGE_ENV', 'production') == 'testing') ? "storage/{$path}" : "storage/vendor/rabsana/trade/{$path}"));
        $assetPath = asset((config('PACKAGE_ENV', 'production') == 'testing') ? "storage/{$path}" : "storage/vendor/rabsana/trade/{$path}");
        return [
            "publicPath"    => $publicPath,
            'assetPath'     => $assetPath
        ];
    }

    private function getPublicPath(string $path): array
    {
        $publicPath = $this->getPrettifiedPath(public_path((config('PACKAGE_ENV', 'production') == 'testing') ? "assets/{$path}" : "vendor/rabsana/trade/{$path}"));
        $assetPath = asset((config('PACKAGE_ENV', 'production') == 'testing') ? "assets/{$path}" : "vendor/rabsana/trade/{$path}");
        return [
            "publicPath"    => $publicPath,
            'assetPath'     => $assetPath
        ];
    }

    private function getPrettifiedPath(string $path): string
    {
        return str_replace('vendor\\orchestra\\testbench-core\\laravel\\public\\', '', $path);
    }
}
