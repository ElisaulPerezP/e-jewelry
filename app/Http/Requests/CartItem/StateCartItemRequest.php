<?php

namespace App\Http\Requests\CartItem;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StateCartItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string>>
     */
    public function rules(): array
    {
        return [
            'state' => [
                'required',
                Rule::in(['in_cart', 'in_order', 'selected', 'paid', 'dispatch']),
            ],
         ];
    }
}
