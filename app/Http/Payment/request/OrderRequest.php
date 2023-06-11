<?php

namespace App\Http\Payment\request;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
            'expiration' => [
                'required',
                'date',
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
