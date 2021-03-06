<?php

namespace Modules\Order\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateTransactionRequest extends BaseFormRequest
{
    public function rules()
    {
        return [
            'amount' => 'numeric|required'
        ];
    }

    public function translationRules()
    {
        return [];
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
