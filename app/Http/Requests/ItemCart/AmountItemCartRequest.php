<?php

namespace App\Http\Requests\ItemCart;

use Illuminate\Foundation\Http\FormRequest;

class AmountItemCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => [
                'required',
                'integer',
                'min:0',
            ],
         ];
    }
}
