<?php

namespace App\Http\Requests\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'order_id' => [
                'required',
                'integer',
            ],
            'reference' => [
                'required',
                'string',
            ],
            'description' => [
                'required',
                'string',
            ],
            'total' => [
                'required',
                'integer',
            ],
            'currency' => [
                'required',

            ],
            'order_state' => [
                'required',

            ],
            'return_url' => [
                'required',
                'string',
                'url',
            ],
            'process_url' => [
                'string',
                'url',
            ],

        ];
    }
}
