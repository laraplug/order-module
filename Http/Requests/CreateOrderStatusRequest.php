<?php

namespace Modules\Order\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

use Modules\Order\Entities\OrderStatus;

class CreateOrderStatusRequest extends BaseFormRequest
{
    public function rules()
    {
        $orderStatusTable = (new OrderStatus())->getTable();
        return [
            'code' => "string|unique:$orderStatusTable,code",
        ];
    }

    public function translationRules()
    {
        return [
            'name' => 'string',
            'description' => 'string|nullable',
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

    public function translationMessages()
    {
        return [];
    }
}
