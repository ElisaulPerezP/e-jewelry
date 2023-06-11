<?php

namespace App\Http\Requests\ItemCart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StateItemCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'state' => [
                'required',
                Rule::in(['in_cart', 'in_order', 'selected']),
            ],
         ];
    }
}
