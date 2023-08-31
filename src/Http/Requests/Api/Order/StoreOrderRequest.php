<?php

namespace Rabsana\Trade\Http\Requests\Api\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Rules\CheckOrderTypeExistsRule;
use Rabsana\Trade\Rules\CheckSymbolExistsRule;
use Rabsana\Trade\Rules\CheckSideIsActiveRule;
use Rabsana\Trade\Rules\ValidateBaseQtyRule;
use Rabsana\Trade\Rules\ValidateNotionalRule;
use Rabsana\Trade\Rules\ValidatePercentPriceRule;
use Rabsana\Trade\Rules\ValidatePriceRule;

class StoreOrderRequest extends FormRequest
{
    public $priceIsRequiredIn = ['LIMIT'];
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
        // strtoupper the data
        request()->merge([
            'symbol'    => strtoupper(request()->get('symbol', '')),
            'side'      => strtoupper(request()->get('side', '')),
            'type'      => strtoupper(request()->get('type', '')),
        ]);

        return [


            'symbol' => array_merge(
                config('rabsana-trade.storeOrderApiRequest.symbol', []),
                [
                    'bail',
                    'required',
                    'max:255',
                    new CheckSymbolExistsRule(),
                ]
            ),


            'side' => array_merge(
                config('rabsana-trade.storeOrderApiRequest.side', []),
                [
                    'bail',
                    'required',
                    function ($attribute, $value, $fail) {
                        if (!in_array(strtoupper($value), ['BUY', 'SELL'])) {
                            $fail(Lang::get("trade::symbolOrder.sideIsNotValid"));
                        }
                    },
                    new CheckSideIsActiveRule()
                ]
            ),


            'type' => array_merge(
                config('rabsana-trade.storeOrderApiRequest.type', []),
                [
                    'bail',
                    'required',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        if (in_array(strtoupper(request()->get('type')), $this->priceIsRequiredIn) && empty(request()->get('price'))) {
                            $fail(Lang::get("trade::symbolOrder.priceIsRequiredIf", ['type' => request()->get('type')]));
                        }
                    },
                    new CheckOrderTypeExistsRule()
                ]
            ),


            'base_qty' => array_merge(
                config('rabsana-trade.storeOrderApiRequest.base_qty', []),
                [
                    'bail',
                    'required',
                    'numeric',
                    new ValidateBaseQtyRule()
                ]
            ),


            'price' => array_merge(
                config('rabsana-trade.storeOrderApiRequest.price', []),
                [
                    'bail',
                    'numeric',
                    new ValidatePriceRule(),
                    new ValidateNotionalRule(),
                    new ValidatePercentPriceRule()
                ]
            ),


            'user_description'  => array_merge(
                config('rabsana-trade.storeOrderApiRequest.user_description', []),
                [
                    'nullable'
                ]
            )


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
            'symbol'                        => Lang::get("trade::symbolOrder.symbol"),
            'side'                          => Lang::get("trade::symbolOrder.side"),
            'type'                          => Lang::get("trade::symbolOrder.type"),
            'base_qty'                      => Lang::get("trade::symbolOrder.base_qty"),
            'price'                         => Lang::get("trade::symbolOrder.price"),
        ];
    }
}
