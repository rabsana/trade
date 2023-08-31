<?php

namespace Rabsana\Trade\Http\Requests\AdminApi\Commission;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;
use Rabsana\Trade\Models\CommissionCondition;

class UpdateCommissionRequest extends FormRequest
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
        $id = $this->route('commission');

        return [
            // commission validations
            'name' => [
                'required',
                'max:255',
                "unique:commissions,name,$id,id"
            ],
            'taker_fee' => [
                'required',
                'numeric',
                'min:0',
                'max:100'
            ],
            // 'maker_fee' => [
            //     'required',
            //     'numeric',
            //     'min:0',
            //     'max:100'
            // ],
            'symbol_quote' => [
                'required',
                'exists:symbols,quote'
            ],
            // condition validations
            'conditions' => [
                'required',
                'array',
                'min:1'
            ],
            'conditions.*.operand' => [
                'required',
                'numeric',
                'min:0'
            ],
            'conditions.*.operator' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, array_keys(CommissionCondition::OPERATORS))) {
                        $fail($value . " is invalid");
                    }
                },
            ],
            'conditions.*.property' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, array_keys(CommissionCondition::PROPERTIES))) {
                        $fail($value . " is invalid");
                    }
                },
            ],
            'conditions.*.period' => [
                'required',
                'numeric',
                'min:0',
            ],
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
            'name'              => Lang::get("trade::commissions.name"),
            'maker_fee'         => Lang::get("trade::commissions.maker_fee"),
            'taker_fee'         => Lang::get("trade::commissions.taker_fee"),
            'symbol_quote'      => Lang::get("trade::commissions.symbol_quote"),
            'operand'           => Lang::get("trade::commissions.operand"),
            'operator'          => Lang::get("trade::commissions.operator"),
            'property'          => Lang::get("trade::commissions.property"),
            'period'            => Lang::get("trade::commissions.period"),
        ];
    }
}
