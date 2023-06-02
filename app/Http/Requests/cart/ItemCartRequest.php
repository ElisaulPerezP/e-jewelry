<?php

namespace App\Http\Requests\cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'product_id' => [
                'required',
                'integer',
            ],
            'amount' => [
                'required',
                'integer',
                'min:0',
            ],
            'item_state' => [
                'required',
                Rule::in(['in_cart', 'saved']),
            ],
         ];
    }
}
