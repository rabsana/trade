<?php

namespace Rabsana\Trade\Http\Requests\AdminApi\Symbol;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class StoreSymbolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'base'                          => array_merge(
                [
                    'required',
                    'max:255'
                ],
                (array) config('rabsana-trade.storeSymbolAdminApiRequest.base', [])
            ),

            'quote'                         => array_merge(
                [
                    'required',
                    'max:255'
                ],
                (array) config('rabsana-trade.storeSymbolAdminApiRequest.quote', [])
            ),
            'pair'                          => 'required|max:255|unique:symbols,pair',
            'base_name'                     => 'required|max:255',
            'quote_name'                    => 'required|max:255',
            'pair_name'                     => 'required|max:255',
            'description'                   => 'nullable',
            'priority'                      => 'nullable|numeric',
            'buy_is_active'                 => 'nullable|boolean|numeric',
            'sell_is_active'                => 'nullable|boolean|numeric',

            'types'                         => 'required|array',
            'types.*'                       => 'numeric|exists:symbol_order_types,id',

            'min_qty'                       => 'required|numeric|min:0.0000000001',
            'max_qty'                       => 'required|numeric|min:0.0000000001',
            'scale_qty'                     => 'required|numeric|integer|min:0|max:10',

            'min_price'                     => 'required|numeric|min:0.0000000001',
            'max_price'                     => 'required|numeric|min:0.0000000001',
            'scale_price'                   => 'required|numeric|integer|min:0|max:10',

            'min_notional'                  => 'required|numeric|min:0.0000000001',
            'max_notional'                  => 'required|numeric|min:0.0000000001',
            'scale_notional'                => 'required|numeric|integer|min:0|max:10',

            'percent_order_price_up'        => 'required|numeric|min:0.0000000001',
            'percent_order_price_down'      => 'required|numeric|min:0.0000000001|max:99',
            'percent_order_price_minute'    => 'required|numeric|integer|min:0',
            'average_price_source_is_market' => 'required|numeric|boolean',
        ];
    }


    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'base'                          => Lang::get("trade::symbol.base"),
            'quote'                         => Lang::get("trade::symbol.quote"),
            'pair'                          => Lang::get("trade::symbol.pair"),
            'base_name'                     => Lang::get("trade::symbol.base_name"),
            'quote_name'                    => Lang::get("trade::symbol.quote_name"),
            'pair_name'                     => Lang::get("trade::symbol.pair_name"),
            'description'                   => Lang::get("trade::symbol.description"),
            'priority'                      => Lang::get("trade::symbol.priority"),
            'buy_is_active'                 => Lang::get("trade::symbol.buy_is_active"),
            'sell_is_active'                => Lang::get("trade::symbol.sell_is_active"),
            'base_image'                    => Lang::get('trade::symbol.base_image'),
            'quote_image'                   => Lang::get('trade::symbol.quote_image'),

            'min_qty'                       => Lang::get("trade::symbol.min_qty"),
            'max_qty'                       => Lang::get("trade::symbol.max_qty"),
            'scale_qty'                     => Lang::get("trade::symbol.scale_qty"),

            'min_price'                     => Lang::get("trade::symbol.min_price"),
            'max_price'                     => Lang::get("trade::symbol.max_price"),
            'scale_price'                   => Lang::get("trade::symbol.scale_price"),

            'min_notional'                  => Lang::get("trade::symbol.min_notional"),
            'max_notional'                  => Lang::get("trade::symbol.max_notional"),
            'scale_notional'                => Lang::get("trade::symbol.scale_notional"),

            'percent_order_price_up'        => Lang::get("trade::symbol.percent_order_price_up"),
            'percent_order_price_down'      => Lang::get("trade::symbol.percent_order_price_down"),
            'percent_order_price_minute'    => Lang::get("trade::symbol.percent_order_price_minute"),
            'average_price_source_is_market' => "منبع محاسبه میانگین قیمت بازار داخلی باشد؟"
        ];
    }
}
