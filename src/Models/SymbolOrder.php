<?php

namespace Rabsana\Trade\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Helpers\Math;
use Illuminate\Support\Str;
use Rabsana\Trade\Helpers\Jdf;
use Rabsana\Trade\Helpers\Json;
use Illuminate\Database\Eloquent\Builder;

class SymbolOrder extends Model
{
    // Use traits


    // Config the model
    const CREATED = 1;
    const FILLING = 2;
    const FILLED = 3;
    const CANCELED = 4;
    const FAILED = 5;

    const LIMIT = 1;
    const MARKET = 2;

    protected $guarded = ['id'];

    protected $appends =
    [
        'base_lower_case',
        'quote_lower_case',
        'pair_lower_case',
        'base_media',
        'quote_media',
        'side_lower_case',
        'side_translated'
    ];


    // Filters

    public function scopeStatusId($query, $statusId = NULL)
    {
        if (is_numeric($statusId)) {
            return $query->where('symbol_order_status_id', $statusId);
        }
        return $query;
    }

    public function scopeCreated($query)
    {
        return $query->where('symbol_order_status_id', self::CREATED);
    }

    public function scopeFilling($query)
    {
        return $query->where('symbol_order_status_id', self::FILLING);
    }

    public function scopeFilled($query)
    {
        return $query->where('symbol_order_status_id', self::FILLED);
    }

    public function scopeCanceled($query)
    {
        return $query->where('symbol_order_status_id', self::CANCELED);
    }

    public function scopeFailed($query)
    {
        return $query->where('symbol_order_status_id', self::FAILED);
    }

    public function scopeLimit($query)
    {
        return $query->where('symbol_order_type_id', self::LIMIT);
    }

    public function scopeCancelable($query)
    {
        return $query->whereIn('symbol_order_status_id', [
            self::CREATED,
            self::FILLING
        ]);
    }

    public function scopeTypeId($query, $typeId = NULL)
    {
        if (is_numeric($typeId)) {
            return $query->where('symbol_order_type_id', $typeId);
        }
        return $query;
    }

    public function scopeOrderableFilter($query, $type = NULL, $id = NULL)
    {
        if (!empty($type) && !empty($id)) {
            return $query->where('orderable_type', $type)
                ->where('orderable_id', $id);
        }

        return $query;
    }

    public function scopeBase($query, $base = NULL)
    {
        if (strtoupper($base) != 'NULL' && !empty($base)) {
            return $query->whereRaw('UPPER(base) = ?', [strtoupper($base)]);
        }

        return $query;
    }

    public function scopeQuote($query, $quote = NULL)
    {
        if (strtoupper($quote) != 'NULL' && !empty($quote)) {
            return $query->whereRaw('UPPER(quote) = ?', [strtoupper($quote)]);
        }

        return $query;
    }

    public function scopePair($query, $pair = NULL)
    {
        if (strtoupper($pair) != 'NULL' && !empty($pair)) {
            return $query->whereRaw('UPPER(pair) = ?', [strtoupper($pair)]);
        }

        return $query;
    }

    public function scopeSearch($query, $search = NULL)
    {
        if (!empty($search)) {
            $search = strtoupper($search);
            return $query->where(function ($q) use ($search) {

                $q->orWhere('id', $search)
                    ->orWhere('base', 'LIKE', "%$search%")
                    ->orWhere('quote', 'LIKE', "%$search%")
                    ->orWhere('pair', 'LIKE', "%$search%")
                    ->orWhere('base_name', 'LIKE', "%$search%")
                    ->orWhere('quote_name', 'LIKE', "%$search%")
                    ->orWhere('pair_name', 'LIKE', "%$search%");
            });
        }

        return $query;
    }

    public function scopeDate($query, $date = NULL)
    {
        if (!empty($date)) {
            $date = gregorian($date, '-') . ' 00:00:00';
            return $query->where('created_at', '>=', $date);
        }

        return $query;
    }

    public function scopeSide($query, $side = NULL)
    {
        if (!empty($side)) {
            return $query->whereRaw('UPPER(side) = ?', [strtoupper($side)]);
        }

        return $query;
    }

    public function scopeTradeable($query)
    {
        return $query->whereIn('symbol_order_status_id', [self::CREATED, self::FILLING]);
    }

    public function scopeToken($query, $token = NULL)
    {
        if (!empty($token)) {
            return $query->where('token', $token);
        }
        return $query;
    }

    public function scopeSearchUser($query, $search = NULL)
    {
        if (!empty($search)) {
            return $query->whereHas('orderable', function (Builder $o) use ($search) {
                $o->where(function ($q) use ($search) {
                    $q->orWhere('name', 'LIKE', "%$search%")
                        ->orWhere('phone', 'LIKE', "%$search%")
                        ->orWhere('nid', 'LIKE', "%$search%")
                        ->orWhere('email', 'LIKE', "%$search%");
                });
            });
        }

        return $query;
    }



    // Relations

    public function status()
    {
        return $this->symbol_order_status();
    }

    public function type()
    {
        return $this->symbol_order_type();
    }

    public function orderable()
    {
        return $this->morphTo();
    }

    public function makers()
    {
        return $this->hasMany(SymbolOrderTrade::class, 'maker_order_id', 'id');
    }

    public function takers()
    {
        return $this->hasMany(SymbolOrderTrade::class, 'taker_order_id', 'id');
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

    public function getSideLowerCaseAttribute(): string
    {
        return strtolower($this->side);
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

    public function getSideAttribute($value): string
    {
        return strtoupper($value);
    }

    public function getSideTranslatedAttribute(): string
    {
        return Lang::get("trade::symbolOrder.$this->side");
    }

    public function getBaseMediaAttribute(): array
    {
        return $this->getMedia($this->base);
    }

    public function getQuoteMediaAttribute(): array
    {
        return $this->getMedia($this->quote);
    }

    public function getOriginalBaseQtyAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getOriginalBaseQtyPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->original_base_qty);
    }

    public function getBaseQtyAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getBaseQtyPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->base_qty);
    }

    public function getOriginalQuoteQtyAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getOriginalQuoteQtyPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->original_quote_qty);
    }

    public function getQuoteQtyAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getQuoteQtyPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->quote_qty);
    }

    public function getFilledBaseQtyAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getFilledBaseQtyPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->filled_base_qty);
    }

    public function getFilledQuoteQtyAttribute($value): string
    {
        return Math::number((float)$value);
    }


    public function getFilledQuoteQtyPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->filled_quote_qty);
    }

    public function getFilledPercentAttribute(): float
    {
        return (float) round(($this->base_qty == 0) ? 0 : Math::divide((float) Math::multiply((float) $this->filled_base_qty, 100), (float) $this->base_qty), 2);
    }

    public function getPriceAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getPricePrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->price);
    }

    public function getCommissionAttribute($value): string
    {
        return Math::number((float)$value);
    }

    public function getCommissionPrettifiedAttribute(): string
    {
        return Math::numberFormat((float)$this->commission);
    }

    public function getCommissionPercentAttribute(): string
    {
        if($this->filled_quote_qty) {
            if($this->side == 'SELL') {
                return ($this->commission * 100) / $this->filled_quote_qty;
            }
            return ($this->commission * 100) / $this->filled_base_qty;  
        }
        return 0;
    }

    public function getCommissionSymbolAttribute(): String
    {
        return ($this->side == 'BUY') ? $this->base : $this->quote;
    }

    public function getReceivedMoneyAttribute(): string
    {
        return $this->side == 'BUY' ? Math::subtract((float) $this->filled_base_qty, $this->commission) : Math::number((float) $this->filled_quote_qty, $this->price);
    }

    public function getSymbolInfoAttribute($value)
    {
        return Json::decode($value);
    }

    public function getCommissionInfoAttribute($value)
    {
        return Json::decode($value);
    }


    public function getCreatedAtAttribute($value): string
    {
        return $value ?: '';
    }

    public function getFillingAtAttribute($value): string
    {
        return $value ?: '';
    }

    public function getFilledAtAttribute($value): string
    {
        return $value ?: '';
    }

    public function getCanceledAtAttribute($value): string
    {
        return $value ?: '';
    }

    public function getFailedAtAttribute($value): string
    {
        return $value ?: '';
    }

    public function getUpdatedAtAttribute($value): string
    {
        return $value ?: '';
    }

    public function getJcreatedAtAttribute(): string
    {
        return Jdf::gtoj($this->created_at);
    }

    public function getJupdatedAtAttribute(): string
    {
        return Jdf::gtoj($this->updated_at);
    }

    public function getJfillingAtAttribute(): string
    {
        return Jdf::gtoj($this->filling_at);
    }

    public function getJfilledAtAttribute(): string
    {
        return Jdf::gtoj($this->filled_at);
    }

    public function getJcanceledAtAttribute(): string
    {
        return Jdf::gtoj($this->canceled_at);
    }

    public function getJfailedAtAttribute(): string
    {
        return Jdf::gtoj($this->failed_at);
    }


    public function getDescriptionAttribute($value): string
    {
        return $value ?: '';
    }

    public function getUserDescriptionAttribute($value): string
    {
        return $value ?: '';
    }

    public function getCancelableAttribute(): int
    {
        return (int) (in_array($this->symbol_order_status_id, [
            self::CREATED,
            self::FILLING
        ]));
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

    public function setSideAttribute($side): void
    {
        $this->attributes['side'] = strtoupper($side);
    }

    public function setBaseQtyAttribute($base_qty): void
    {
        $this->attributes['base_qty'] = Math::number((float)$base_qty);
    }

    public function setOriginalBaseQtyAttribute($original_base_qty): void
    {
        $this->attributes['original_base_qty'] = Math::number((float)$original_base_qty);
    }

    public function setQuoteQtyAttribute($quote_qty): void
    {
        $this->attributes['quote_qty'] = Math::number((float)$quote_qty);
    }

    public function setOriginalQuoteQtyAttribute($original_quote_qty): void
    {
        $this->attributes['original_quote_qty'] = Math::number((float)$original_quote_qty);
    }

    public function setFilledBaseQtyAttribute($filled_base_qty): void
    {
        $this->attributes['filled_base_qty'] = Math::number((float)$filled_base_qty);
    }

    public function setFilledQuoteQtyAttribute($filled_quote_qty): void
    {
        $this->attributes['filled_quote_qty'] = Math::number((float)$filled_quote_qty);
    }

    public function setPriceAttribute($price): void
    {
        $this->attributes['price'] = Math::number((float)$price);
    }

    public function setCommissionAttribute($commission): void
    {
        $this->attributes['commission'] = Math::number((float)$commission);
    }

    public function setCommissionPercentAttribute($commission_percent): void
    {
        $this->attributes['commission_percent'] = Math::number((float)$commission_percent);
    }

    public function setTokenAttribute($token): void
    {
        $this->attributes['token'] = self::generateUniqueToken($token);
    }

    public static function generateUniqueToken($token = NULL)
    {
        // if the token have provided we check it is unique but if its not unique: we create new one
        // if the token have not provided: we create new one
        $isSecondTime = false;
        do {
            $token = (!empty($token) && !$isSecondTime) ? $token : Str::random(40) . time();
            $isSecondTime = true;
        } while (SymbolOrder::token($token)->exists());

        return $token;
    }

    public function setSymbolInfoAttribute($value)
    {
        $this->attributes['symbol_info'] = Json::encode($value);
    }

    public function setCommissionInfoAttribute($commission_info)
    {
        $this->attributes['commission_info'] = Json::encode($commission_info);
    }

    // Extra methods

    private function symbol_order_status()
    {
        return $this->belongsTo(SymbolOrderStatus::class);
    }

    private function symbol_order_type()
    {
        return $this->belongsTo(SymbolOrderType::class);
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

    public static function cancel(SymbolOrder $order): void
    {
        $order->update([
            'symbol_order_status_id' => self::CANCELED,
            'canceled_at'            => now()
        ]);
    }

    public function averagePrice()
    {
        return  $this->filled_base_qty != 0 ? $this->total_price / $this->filled_base_qty : 0;
    }
}
