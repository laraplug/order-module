<?php

namespace Modules\Order\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function rules()
    {
        return [
            'payment_postcode' => 'numeric',
            'payment_address' => 'numeric',
            'payment_address_detail' => 'numeric',

            'shipping_fullname' => 'numeric',
            'shipping_postcode' => 'numeric',
            'shipping_address' => 'numeric',
            'shipping_address_detail' => 'numeric',

            'shipping_method' => 'numeric',
            'shipping_code' => 'numeric',
            'shipping_custom_field' => 'numeric',
            'note' => 'numeric',

            'total' => 'numeric',

            'order_status_id' => 'numeric',
            'currency_code' => 'numeric',
            'currency_value' => 'numeric',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }

}
